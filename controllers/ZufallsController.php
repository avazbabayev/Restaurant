<?php

use Aspera\Spreadsheet\XLSX\Reader;

class ZufallsController extends \Slim\Controller
{

    public function load_xlsx()
    {
        $reader = new Reader();
        $reader->open('Essensziele.xlsx');
        try {
            foreach ($reader as $row) {
                if (!empty(trim($row[0])) and trim($row[0]) != 'Name') {
                    $restaurant = ORM::for_table('restaurants')
                        ->where_equal('name', trim($row[0]))
                        ->find_one();
                    if (!$restaurant) {
                        $restaurant = ORM::for_table('restaurants')
                            ->create();
                        $restaurant->name = trim($row[0]);
                        $restaurant->address = trim($row[4]);
                        $restaurant->save();
                    }
                    $category = ORM::for_table('r_category')
                        ->where_equal('name', trim($row[5]))
                        ->find_one();
                    if (!$category) {
                        $category = ORM::for_table('r_category')
                            ->create();
                        $category->name = trim($row[5]);
                        $category->save();
                    }
                    $r_features = ORM::for_table('r_features')
                        ->where_equal('restaurant', $restaurant->id)
                        ->find_one();
                    if (!$r_features) {
                        $r_features = ORM::for_table('r_features')
                            ->create();
                    }
                    $r_features->restaurant = $restaurant->id;
                    $r_features->distance_v = strlen(trim($row[1]));
                    $r_features->price_v = strlen(trim($row[2]));
                    $r_features->veggie_v = strlen(trim($row[3]));
                    $r_features->category = $category->id;
                    $r_features->save();
                }
            }
        } catch (Exception $e) {
            $reader->close();
            $this->redirect('/zufalls/index?ms=error');
        }
        $reader->close();
        $this->redirect('/zufalls/index?ms=success');
    }


    public function index()
    {
        $this->render('zufalls.index.php', array(
            'title' => 'Zufallsgenerator'
        ));
    }

    public function get_categories()
    {
        $data = ORM::for_table('r_category')
            ->find_array();
        echo json_encode($data);
    }

    public function get_raw_data()
    {

        $obj = json_decode(file_get_contents('php://input'));
        try {
            if (!empty($obj)) {
                $alles = ORM::for_table('r_category')->where_equal('name', 'Alles')
                    ->find_one()->id;
                $data = ORM::for_table('restaurants')
                    ->table_alias('rt')
                    ->left_outer_join('r_features', 'rt.id = r_f.restaurant', 'r_f')
                    ->left_outer_join('r_category', 'r_c.id = r_f.category', 'r_c')
                    ->select_expr('rt.*,r_f.distance_v,r_f.veggie_v,r_f.price_v,r_c.name as category');
                if (!empty($obj->category) and !in_array($alles, $obj->category)) {
                    $data = $data->where_in('r_c.id', $obj->category);
                }
                foreach ($obj as $key => $value) {
                    if (trim($key) != 'category' and !empty(trim($value))) {
                        $data = $data->where_lte('r_f.'.trim($key), trim($value));
                    }
                }
                $data = $data->find_array();
                echo json_encode($data);
            }
        } catch (Exception $exception) {
            echo json_encode(['ms'=>$exception->getMessage()]);

        }

    }

}
	
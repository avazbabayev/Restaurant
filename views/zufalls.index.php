<div id="app">
    <template>
        <div class="container">
            <?php
            if (isset($_GET['ms'])) {
                if (trim($_GET['ms']) == 'success') {
                    echo '<el-alert title="Die Daten wurden erfolgreich hochgeladen" type="success" show-icon> </el-alert>';
                } elseif (trim($_GET['ms']) == 'error') {
                    echo '<el-alert title="Beim Hochladen ist ein Fehler aufgetreten.  Bitte kontaktieren Sie den Administrator!" type="error" show-icon> </el-alert>';
                }
            }
            ?>
            <div class="row">
                <div class="col col-lg-3 col-md-6 col-xs-12">
                    <el-select
                            v-model="val_category"
                            multiple
                            collapse-tags
                            placeholder="Kategorie">
                        <el-option
                                v-for="item in category"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </div>
                <div class="col col-lg-3 col-md-6 col-xs-12">
                    <el-select v-model="val_distance" placeholder="Entfernung">
                        <el-option
                                v-for="item in distance"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </div>
            </div>
            <div class="row">
                <div class="col col-lg-3 col-md-6 col-xs-12">
                    <el-select v-model="val_price" placeholder="Preis">
                        <el-option
                                v-for="item in price"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </div>
                <div class="col col-lg-3 col-md-6 col-xs-12">
                    <el-select v-model="val_veggi" placeholder="Veggie-Tauglich">
                        <el-option
                                v-for="item in veggi"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>

                </div>
            </div>
            <div class="row">
                <div class="col col-md-8">
                    <el-row :gutter="20">
                        <el-col>
                            <el-button @click="show()" type="primary" plain>Randomize</el-button>
                            <el-button @click="reset()" type="info" plain>Reset</el-button>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>

        <el-table ref="posTable" :data="tableData" style="width: 80%" empty-text="Keine Daten"
                  v-if="render">
            <el-table-column width="150">
                <template slot-scope="scope">
                    <el-button size="mini" @click="choose(scope.row)">wählen</el-button>
                </template>
            </el-table-column>
            <el-table-column prop="name" label="Restaurant" width="150">
            </el-table-column>
            <el-table-column prop="address" label="Addresse" width="300">
            </el-table-column>
            <el-table-column prop="distance_v" label="Entfernung" width="150">
            </el-table-column>
            <el-table-column prop="price_v" label="Preis" width="150">
            </el-table-column>
            <el-table-column prop="veggie_v" label="Veggie-Tauglich" width="150">
            </el-table-column>
            <el-table-column prop="category" label="Kategorie" width="150">
            </el-table-column>
        </el-table>
</div>


</template>
</div>
<style>
    .container {
        float: left;
    }
</style>
<script>
    new Vue({
        el: '#app',
        mounted() {
            axios.get('<?php echo $controller->createUrl('/zufalls/get_categories'); ?>').then((r) => {
                let cat;
                if (typeof r.data === 'object') cat = r.data;
                else cat = JSON.parse(r.data.trim());
                this.category = cat;
            });
        },
        data: function () {
            return {
                category: [{id: '', name: ''}],
                veggi: [{id: 1, name: '*'}, {id: 2, name: '**'}, {id: 3, name: '***'}],
                distance: [{id: 1, name: '*'}, {id: 2, name: '**'}, {id: 3, name: '***'}],
                price: [{id: 1, name: '*'}, {id: 2, name: '**'}, {id: 3, name: '***'}],
                val_category: [],
                val_veggi: '',
                val_distance: '',
                val_price: '',
                tableData: [],
                render: false,

            }
        },

        methods: {
            reset() {
                this.val_category = [];
                this.val_distance = '';
                this.val_price = '';
                this.val_veggi = '';
                this.tableData = [];
                this.render = false;
            },
            show() {
                let posdata = {}
                posdata.category = this.val_category;
                posdata.price_v = this.val_price;
                posdata.veggie_v = this.val_veggi;
                posdata.distance_v = this.val_distance;
                axios.post('<?php echo $controller->createUrl('/zufalls/get_raw_data'); ?>', posdata)
                    .then((r) => {
                        var d = JSON.parse(r.data.trim());
                        if (d.ms) {
                            alert('Bitte geben Sie die Daten ein, indem Sie auf "Daten hochladen" klicken.');
                        } else {
                            this.tableData = d;

                            this.tableData.forEach((row) => {
                                row.price_v = '*'.repeat(row.price_v);
                                row.veggie_v = '*'.repeat(row.veggie_v);
                                row.distance_v = '*'.repeat(row.distance_v);
                            })
                            this.render = true;
                        }
                    })
            },
            choose(row) {
                console.log(index)
            }
        }
    });
</script>
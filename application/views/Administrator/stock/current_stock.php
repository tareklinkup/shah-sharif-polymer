<style>
.v-select {
    margin-bottom: 5px;
}

.v-select .dropdown-toggle {
    padding: 0px;
}

.v-select input[type=search],
.v-select input[type=search]:focus {
    margin: 0px;
}

.v-select .vs__selected-options {
    overflow: hidden;
    flex-wrap: nowrap;
}

.v-select .selected-tag {
    margin: 2px 0px;
    white-space: nowrap;
    position: absolute;
    left: 0px;
}

.v-select .vs__actions {
    margin-top: -5px;
}

.v-select .dropdown-menu {
    width: auto;
    overflow-y: auto;
}
</style>
<div id="stock">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
            <div class="form-group" style="margin-top:10px;">
                <label class="col-sm-1 control-label no-padding"> Select Branch </label>
                <div class="col-sm-2">
                    <select v-model="branchId" id="" class="form-control no-padding" style="border-radius: 4px;">
                        <option value="" selected disabled>Select</option>
                        <option v-for="(mbranch,index) in onlyBranch" :key="index" :value="mbranch.brunch_id">
                            {{mbranch.Brunch_name}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:10px;">
                <label class="col-sm-1  control-label no-padding-right"> Select Type </label>
                <div class="col-sm-2">
                    <v-select v-bind:options="searchTypes" v-model="selectedSearchType" label="text"
                        v-on:input="onChangeSearchType"></v-select>
                </div>
            </div>

            <!-- <div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'current'">
				<label class="col-sm-1">Model</label>
				<div class="col-sm-2">
					<select v-model="model_name" id="" style="width: 150px;border-radius: 4px;">
						<option value="" disabled>Select</option>
						<option v-for="model in models" :value="model.model_id">{{ model.model_name }}</option>
					</select>
				</div>
			</div> -->
            <div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'category'">
                <div class="col-sm-2" style="margin-left:15px;">
                    <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name">
                    </v-select>
                </div>
            </div>

            <div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'product_size'">
                <div class="col-sm-2" style="margin-left:15px;">
                    <v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
                </div>
            </div>

            <!---size wise stock -->

            <div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'product_size'">
                <div class="col-sm-2" style="margin-left:15px;">
                    <v-select v-bind:options="sizes" v-model="selectedSize" label="Display_Size"></v-select>
                </div>
            </div>

            <div class="form-group" style="margin-top:10px;" v-if="selectedSearchType.value == 'brand'">
                <div class="col-sm-2" style="margin-left:15px;">
                    <v-select v-bind:options="brands" v-model="selectedBrand" label="brand_name"></v-select>
                </div>
            </div>

            <div class="form-group" style="margin-top:10px;">
                <div class="col-sm-2" style="margin-left:15px;">
                    <input type="date" class="form-control" v-model="date">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2" style="margin-left:15px;">
                    <input type="button" class="btn btn-primary" value="Show Report" v-on:click="getStock"
                        style="margin-top:0px;border:0px;height:28px;">
                </div>
            </div>

        </div>
    </div>
    <div class="row" v-if="searchType != null" style="display:none"
        v-bind:style="{display: searchType == null ? 'none' : ''}">
        <div class="col-md-12">
            <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" id="stockContent">
                <table class="table table-bordered" v-if="searchType == 'current'" style="display:none"
                    v-bind:style="{display: searchType == 'current' ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Product Size</th>
                            <th>Category</th>
                            <th>Current Quantity</th>
                            <th>Rate</th>
                            <th>Stock Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in stock">
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td>{{ product.Size_Name }}</td>
                            <td>{{ product.ProductCategory_Name }}</td>
                            <td>{{ product.current_quantity }} {{ product.Unit_Name }}</td>
                            <td>{{ product.Product_Purchase_Rate | decimal }}</td>
                            <td>{{ product.stock_value | decimal }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:right;">Total Stock Value</th>
                            <th>{{ totalStockValue | decimal }}</th>
                        </tr>
                    </tfoot>
                </table>

                <table class="table table-bordered" v-if="searchType != 'current' && searchType != null"
                    style="display:none;"
                    v-bind:style="{display: searchType != 'current' && searchType != null ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Product Size</th>
                            <th>Model Name</th>
                            <th>Category</th>
                            <th>Purchased Quantity</th>
                            <th>Purchase Returned Quantity</th>
                            <th>Damaged Quantity</th>
                            <th>Sold Quantity</th>
                            <th>Free Quantity</th>
                            <th>Sales Returned Quantity</th>
                            <th>Transferred In Quantity</th>
                            <th>Transferred Out Quantity</th>
                            <th>Current Quantity</th>
                            <th>Rate</th>
                            <th>Stock Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in stock">
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td>{{ product.Size_Name }}</td>
                            <td>{{ product.model_name }}</td>
                            <td>{{ product.ProductCategory_Name }}</td>
                            <td>{{ product.purchased_quantity }}</td>
                            <td>{{ product.purchase_returned_quantity }}</td>
                            <td>{{ product.damaged_quantity }}</td>
                            <td>{{ product.sold_quantity }}</td>
                            <td>{{ product.free_quantity }}</td>
                            <td>{{ product.sales_returned_quantity }}</td>
                            <td>{{ product.transferred_to_quantity}}</td>
                            <td>{{ product.transferred_from_quantity}}</td>
                            <td>{{ product.current_quantity }} {{ product.Unit_Name }}</td>
                            <td>{{ product.Product_Purchase_Rate | decimal }}</td>
                            <td>{{ product.stock_value | decimal }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="15" style="text-align:right;">Total Stock Value</th>
                            <th>{{ totalStockValue | decimal }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
Vue.component('v-select', VueSelect.VueSelect);
new Vue({
    el: '#stock',
    data() {
        return {
            searchTypes: [{
                    text: 'Current Stock',
                    value: 'current'
                },
                {
                    text: 'Total Stock',
                    value: 'total'
                },
                {
                    text: 'Category Wise Stock',
                    value: 'category'
                },
                {
                    text: 'Product&Size Wise Stock',
                    value: 'product_size'
                },
                // {
                //     text: 'Size Wise Stock',
                //     value: 'size'
                // },
                //{text: 'Brand Wise Stock', value: 'brand'}
            ],
            selectedSearchType: {
                text: 'select',
                value: ''
            },
            searchType: null,
            date: moment().format('YYYY-MM-DD'),
            categories: [],
            selectedCategory: null,
            products: [],
            selectedProduct: null,
            selectedSize: null,
            sizes: [],
            brands: [],
            selectedBrand: null,
            selectionText: '',

            model_name: '',
            models: [],

            stock: [],
            totalStockValue: 0.00,
            branchId: '',
            branches: [],
            brunch_id: "<?php echo $this->session->userdata('BRANCHid'); ?>",
            Brunch_name: "<?php echo $this->session->userdata('Brunch_name'); ?>"

        }
    },
    filters: {
        decimal(value) {
            return value == null ? '0.00' : parseFloat(value).toFixed(2);
        }
    },
    created() {
        this.getBranches();
        // this.getModels();
    },
    computed: {
        onlyBranch() {
            return this.branches.filter(ele => (ele.MainBranch_Id == this.brunch_id || ele.brunch_id == this
                .brunch_id));
        }
    },
    methods: {
        getBranches() {
            axios.get('/get_branches').then(res => {
                this.branches = res.data;
            })
        },
        // getModels() {
        // 	axios.get('/get_models').then(res => {
        // 		this.models = res.data;
        // 	})
        // },
        getStock() {
            this.searchType = this.selectedSearchType.value;
            let url = '';
            let parameters = {};

            if (this.searchType == 'current') {
                url = '/get_current_stock';
            } else {
                url = '/get_total_stock';
                parameters.date = this.date;
            }

            this.selectionText = "";

            if (this.searchType == 'category' && this.selectedCategory == null) {
                alert('Select a category');
                return;
            } else if (this.searchType == 'category' && this.selectedCategory != null) {
                parameters.categoryId = this.selectedCategory.ProductCategory_SlNo;
                this.selectionText = "Category: " + this.selectedCategory.ProductCategory_Name;
            }

            if (this.searchType == 'current' && this.model_name != '') {
                parameters.model = this.model_name;
            }

            if (this.searchType == 'product_size' && this.selectedProduct == null) {
                alert('Select a product');
                return;
            } else if (this.searchType == 'product_size' && (this.selectedProduct != null || this
                    .selectedSize != null)) {
                parameters.productId = this.selectedProduct.Product_SlNo;

                if (this.selectedSize != null && this.selectedSize.Size_SlNo != null) {
                    parameters.size_id = this.selectedSize.Size_SlNo;
                } else {
                    parameters.size_id = null;
                }

                //parameters.size_id = this.selectedSize.Size_SlNo;
                this.selectionText = "product_size: " + this.selectedProduct.display_text;
            }

            // if (this.searchType == 'size' && this.selectedSize == null) {
            //     alert('Select a size');
            //     return;
            // } else if (this.searchType == 'size' && this.selectedSize != null) {
            //     parameters.sizeId = this.selectedSize.Size_SlNo;
            //     this.selectionText = "size: " + this.selectedSize.Display_Size;
            // }

            if (this.searchType == 'brand' && this.selectedBrand == null) {
                alert('Select a brand');
                return;
            } else if (this.searchType == 'brand' && this.selectedBrand != null) {
                parameters.brandId = this.selectedBrand.brand_SiNo;
                this.selectionText = "Brand: " + this.selectedBrand.brand_name;
            }
            if (this.branchId == '') {
                alert('Select a Branch');
                return;
            } else {
                parameters.branchId = this.branchId;
            }

            // console.log(parameters);
            // return;

            axios.post(url, parameters).then(res => {
                if (this.searchType == 'current') {
                    this.stock = res.data.stock.filter((pro) => pro.current_quantity != 0);
                } else {
                    this.stock = res.data.stock;
                }
                this.totalStockValue = res.data.totalValue;
            })
        },
        onChangeSearchType() {

            if (this.selectedSearchType.value == 'category' && this.categories.length == 0) {
                this.getCategories();
            } else if (this.selectedSearchType.value == 'brand' && this.brands.length == 0) {
                this.getBrands();
            } else if (this.selectedSearchType.value == 'product_size' && this.products.length == 0) {
                this.getProducts();
                this.getSize();
            }

        },
        getCategories() {
            axios.get('/get_categories').then(res => {
                this.categories = res.data;
            })
        },
        getProducts() {
            axios.post('/get_products', {
                isService: 'false'
            }).then(res => {
                this.products = res.data;
            })
        },

        getSize() {
            axios.post('/get_sizes', {
                isService: 'false'
            }).then(res => {
                this.sizes = res.data;
            })
        },

        // getBrands() {
        // 	axios.get('/get_brands').then(res => {
        // 		this.brands = res.data;
        // 	})
        // },
        async print() {
            let reportContent = `
					<div class="container-fluid">
						<h4 style="text-align:center">${this.selectedSearchType.text} Report</h4 style="text-align:center">
						<h6 style="text-align:center">${this.selectionText}</h6>
					</div>
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#stockContent').innerHTML}
							</div>
						</div>
					</div>
				`;

            var reportWindow = window.open('', 'PRINT',
                `height=${screen.height}, width=${screen.width}, left=0, top=0`);
            reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

            reportWindow.document.body.innerHTML += reportContent;

            reportWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            reportWindow.print();
            reportWindow.close();
        }
    }
})
</script>
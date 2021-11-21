var mixin =  {
    data() {
        return {
            baseUrl: baseUrl,
            dataList: [],
            paginate_data:[],
            filterDataList: [],
            formData: {},
            modalData: [],
            imageData: {},
            emptyImageData: {},
            activeModal: '',
            filter: {},
            pageSize: [10, 25, 50, 100, 'All'],
            gridData: {},
            errorsList: {},
            modalLoading: false,
            previousPageTotal: 0,
            sort: 'id',
            order: 'desc',
            listPerPage: 10,
            currentPageNo: 1,
            totalPage:1,
            emptyImagePath: baseUrl + '/img/no_image.png',
            dateFormat: "yyyy-MM-dd",
            buttonDisabled: false,
            pageLoader:false,
            deleteIdList:[],
            refIdList:[],
            refDataList:[],
            serial_details:[],
            current_item_info:{},
            serial_checkList : [],
            serial_all_check:0,
            serial_all_select_text:'',
            numberWords:{
                a: ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '],
                b: ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']
            },
            dateOptions: {
                format: 'DD/MM/YYYY',
                showClear:true,
                showClose:true,
                useCurrent: true,
                showTodayButton:true
            }   
        }
    },
    methods: {
        openModal(modalId = 'addForm', dataUrl = false, reset = true, callback, options = {}) {
            const _this = this;
            _this.$validator.reset();
            _this.activeModal = modalId;
            $('#' + modalId).modal({
                backdrop: 'static'
            });
            $('#' + modalId).modal('show');
            if (reset) {
                _this.formReset(true);
            }
            if (dataUrl) {
                _this.pageLoader = true;
                let URL = _this.baseUrl + '/' + dataUrl;
                axios.get(URL).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if(res.data.status == 0){
                                _this.showToster({
                                    status: 0,
                                    message: res.data.message
                                });
                            }else{
                                if (typeof res.data.formData !== 'undefined') {
                                    _this.formData = res.data.formData;
                                }
                                _this.modalData = res.data;
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        _this.pageLoader = false;
                        _this.showToster({
                            status: 0,
                            message: 'opps! something went wrong'
                        });
                    })
            } else {
                if (typeof callback === 'function') {
                    callback();
                }
            }
        },
        closeModal(modalId = this.activeModal, callback) {
            $('#' + modalId).modal('hide');
            if (typeof callback === 'function') {
                callback();
            }
        },
        submitForm(formData = this.formData, options = {}, callback) {
            //console.log(formData); return false;
            const _this = this;
            var redirectUrl = (!_this.isEmpty(options) && typeof options.redirectUrl !== 'undefined' && options.redirectUrl) ? options.redirectUrl : false;
            var url = (!_this.isEmpty(options) && typeof options.url !== 'undefined' && options.url) ? options.url : false;
            var urlPostfix = '';
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    _this.pageLoader = true;
                    _this.buttonDisabled = true;
                    axios.post(URL, formData).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1) {
                                if (!_this.formData.id) {
                                    _this.formReset();
                                    _this.getResults(1);
                                } else {
                                    _this.getResults(_this.currentPageNo);
                                    $('#' + _this.activeModal).modal('hide');
                                }
                                if (typeof callback === 'function') {
                                    callback({
                                        'inputData': formData
                                    });
                                }
                                if (redirectUrl) {
                                    _this.$router.push({
                                        path: redirectUrl
                                    });
                                }
                            }
                            _this.pageLoader = false;
                            _this.buttonDisabled = false;
                            _this.showToster(res.data);
                        }

                    }).catch(function(error) {
                        _this.pageLoader = false;
                        _this.buttonDisabled = false;
                        if (error.response.status == 422) {
                            _this.assignValidationError(error.response.data.errors);
                            var msg = 'Opps! Invalid data';
                        } else {
                            var msg = 'Opps! something went wrong';
                        }
                        _this.showToster({
                            status: 0,
                            message: msg
                        });

                    });
                }
            });
        },
        submitFileForm(formData = this.formData, options = {}, callback) {
            const _this = this;
            var redirectUrl = (!_this.isEmpty(options) && typeof options.redirectUrl !== 'undefined' && options.redirectUrl) ? options.redirectUrl : false;
            var url = (!_this.isEmpty(options) && typeof options.url !== 'undefined' && options.url) ? options.url : false;
            var urlPostfix = '';
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    var formReqData = new FormData();
                    Object.keys(_this.imageData).forEach(key => {
                        formReqData.append(key, _this.imageData[key]);
                    });
                    Object.keys(formData).forEach(key => {
                        formReqData.append(key, formData[key]);
                    });

                    _this.pageLoader = true;
                    _this.buttonDisabled = true;
                    axios.post(URL, formReqData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1) {
                                if (!_this.formData.id) {
                                    _this.formReset(false);
                                    _this.getResults(1);
                                } else {
                                    _this.getResults(_this.currentPageNo);
                                    $('#' + _this.activeModal).modal('hide');
                                }
                                if (typeof callback === 'function') {
                                    callback({
                                        'inputData': formData
                                    });
                                }
                                if (redirectUrl) {
                                    _this.$router.push({
                                        path: redirectUrl
                                    });
                                }
                            }
                            _this.pageLoader = false;
                            _this.buttonDisabled = false;
                            _this.showToster(res.data);
                        }

                    }).catch(function(error) {
                        _this.pageLoader = false;
                        _this.buttonDisabled = false;
                        if (error.response.status == 422) {
                            _this.assignValidationError(error.response.data.errors);
                            var msg = 'Opps! Invalid data';
                        } else {
                            var msg = 'Opps! something went wrong';
                        }
                        _this.showToster({
                            status: 0,
                            message: msg
                        });

                    });
                }
            });
        },
        submitListForm(formData = this.formData, options = {}, callback) {
            const _this = this;
            var redirectUrl = (!_this.isEmpty(options) && typeof options.redirectUrl !== 'undefined' && options.redirectUrl) ? options.redirectUrl : false;
            var url = (!_this.isEmpty(options) && typeof options.url !== 'undefined' && options.url) ? options.url : false;
            var urlPostfix = '';
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    _this.buttonDisabled = true;
                    _this.pageLoader = true;
                    axios.post(URL, formData).then((res) => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            if (res.data.status == 1) {
                                if (!_this.formData.id) {
                                    _this.formReset();
                                }
                                if (typeof callback === 'function') {
                                    callback();
                                }
                                if (redirectUrl) {
                                    _this.$router.push({
                                        path: redirectUrl
                                    });
                                }
                            }
                            _this.pageLoader = false;
                            _this.buttonDisabled = false;
                            _this.showToster(res.data);
                        }

                    }).catch(function(error) {
                        _this.pageLoader = false;
                        _this.buttonDisabled = false;
                        if (error.response.status == 422) {
                            _this.assignValidationError(error.response.data.errors);
                            var msg = 'Opps! Invalid data';
                        } else {
                            var msg = 'Opps! something went wrong';
                        }
                        _this.showToster({
                            status: 0,
                            message: msg
                        });

                    });
                }
            });
        },
        getResults(page = 1) {
            const _this = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            var objParams = {
                listPerPage: _this.listPerPage,
                page: page,
                sort: _this.sort,
                order: _this.order
            };
            var dataParams = Object.assign(_this.filter, objParams);
            let URL = _this.baseUrl + '/' + _this.dataUrl();
            _this.currentPageNo = page;
            _this.pageLoader = true;
            axios.get(URL, {
                params: dataParams
            }).then(res => {
                if (res.data.status == 'logout' || res.data.status == 0) {
                    if(res.data.status == 0){
                        _this.showToster(res.data);
                    }else{
                        window.location.href = res.data.url;
                    }
                } else {
                    _this.dataList = res.data;
                    if(_this.listPerPage=='All'){
                        _this.paginate_data = res.data.paginate_data;
                        _this.totalPage = 1;
                        _this.previousPageTotal = 0;
                    }else{
                        _this.paginate_data = res.data.paginate_data.data; 
                        _this.totalPage = _this.dataList.paginate_data.last_page;
                        if (page > 1) {
                            _this.previousPageTotal = _this.listPerPage * (page - 1);
                        } else {
                            _this.previousPageTotal = 0;
                        }
                    }
                    if (typeof res.data.filter !== 'undefined') {
                        _this.filter = res.data.filter;
                    }
                }
                _this.pageLoader = false;
            }).catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
            });
        },
        getResultsWithoutPagi(url = '') {
            const _this = this;
            var urlPostfix = '';
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;

            var objParams = {
                sort: _this.sort,
                order: _this.order
            };
            var dataParams = Object.assign(_this.filter, objParams);
            _this.pageLoader = true;
            axios.get(URL, {
                params: dataParams
            }).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    _this.dataList = res.data;
                    if (typeof res.data.formData !== 'undefined') {
                        _this.formData = res.data.formData;
                    }
                    _this.pageLoader = false;
                    // for sidebar height calculation
                    
                }
            }).catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
                

            })
        },
        
        // this function for reduce filter traffic
        getPaginateFilter(page = 1, url = false) {
            const _this = this;
            var urlPostfix = '';
            if (typeof page === 'undefined') {
                page = 1;
            }
            var objParams = {
                listPerPage: _this.listPerPage,
                page: page,
                sort: _this.sort,
                order: _this.order
            };
            var dataParams = Object.assign(_this.filter, objParams);
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;
            _this.currentPageNo = page;
            _this.pageLoader = true;
            axios.get(URL, {
                params: dataParams
            }).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    _this.filterDataList = res.data;
                    _this.pageLoader = false;
                    if (page > 1) {
                        _this.previousPageTotal = _this.listPerPage * (page - 1);
                    } else {
                        _this.previousPageTotal = 0;
                    }
                    
                }
            }).catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
                

            })
        },
        getReportData(url = false) {
            const _this = this;
            var urlPostfix = '';
            var objParams = {
                sort: _this.sort,
                order: _this.order
            };
            var dataParams = Object.assign(_this.filter, objParams);
            if (url) {
                urlPostfix = url;
            } else {
                urlPostfix = _this.dataUrl();
            }
            const URL = _this.baseUrl + '/' + urlPostfix;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    _this.pageLoader = true;
                    _this.filter.is_filter = 'yes';
                    axios.get(URL, {
                        params: dataParams
                    }).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.filterDataList = res.data;
                            _this.pageLoader = false;
                            
                        }
                    }).catch(error => {
                        _this.pageLoader = false;
                        _this.showToster({
                            status: 0,
                            message: 'opps! something went wrong'
                        });
                        

                    });
                }
            });

        },
        deleteItem(dataId, options = {}) {
            const _this = this;
            let URL = _this.baseUrl + '/' + _this.dataUrl() + '/' + dataId;
            Swal.fire({
                title: 'Are you sure..??',
                text: 'Data will be delete Permanently??',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showCloseButton: true,
            }).then((result) => {
                if (result.value) {
                    _this.pageLoader = true;
                    axios.delete(URL).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if (res.data.status == 0) {
                                _this.showToster(res.data);
                            } else {
                                Swal.fire('Deleted', 'You successfully deleted this file', 'success');
                                if (Object.keys(_this.paginate_data).length > 1) {
                                    _this.getResults(_this.currentPageNo);
                                } else {
                                    _this.getResults(_this.currentPageNo - 1);
                                }
                            }
                        }
                    }).catch(function(error) {
                        _this.pageLoader = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'Whoops..!! something went wrong',
                            icon: 'error'
                        });
                    });
                }
            });

        },
        deleteListItem(dataId, url = false) {
            const _this = this;
            if (url) {
                var URL = _this.baseUrl + '/' + url + '/' + dataId;
            } else {
                var URL = _this.baseUrl + '/' + _this.dataUrl() + '/' + dataId;
            }
            Swal.fire({
                title: 'Are you sure..??',
                text: 'Data will be delete Permanently??',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showCloseButton: true,
            }).then((result) => {
                if (result.value) {
                    _this.pageLoader = true;
                    axios.delete(URL).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if (res.data.status == 0) {
                                _this.showToster(res.data);
                            } else {
                                Swal.fire('Deleted', 'You successfully deleted this file', 'success');
                                _this.getResultsWithoutPagi();
                            }
                        }
                    }).catch(function(error) {
                        _this.pageLoader = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'Whoops..!! something went wrong',
                            icon: 'error'
                        });
                    });
                }
            });

        },
        deleteArrayItem(url = false) {
            const _this = this;
            if(_this.deleteIdList.length>0){
                if (url) {
                    var URL = _this.baseUrl + '/' + url+ '/0';
                } else {
                    var URL = _this.baseUrl + '/' + _this.dataUrl()+ '/0';
                }
                Swal.fire({
                    title: 'Are you sure..??',
                    text: 'Data will be delete Permanently??',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    showCloseButton: true,
                }).then((result) => {
                    if (result.value) {
                        _this.pageLoader = true;
                        axios.delete(URL,{
                            data:{
                                delIds:_this.deleteIdList
                            }
                        }).then(res => {
                            if (res.data.status == 'logout') {
                                window.location.href = res.data.url;
                            } else {
                                _this.pageLoader = false;
                                if (res.data.status == 0) {
                                    _this.showToster(res.data);
                                } else {
                                    Swal.fire('Deleted', 'You successfully deleted this file', 'success');
                                    _this.deleteIdList = [];
                                    _this.getResults(1);

                                }
                            }
                        }).catch(function(error) {
                            _this.pageLoader = false;
                            Swal.fire({
                                title: 'Error',
                                text: 'Whoops..!! something went wrong',
                                icon: 'error'
                            });
                        });
                    }
                });
            }else{
                alert('Please select a item');
            }

        },
        voucherApproval(url,dataId=0) {
            const _this = this;
            let URL = _this.baseUrl + '/' + url + '/' + dataId;
            Swal.fire({
                title: 'Are you sure..??',
                text: 'Voucher will be Approved',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showCloseButton: true,
            }).then((result) => {
                if (result.value) {
                    _this.pageLoader = true;
                    axios.post(URL,{appIds:_this.deleteIdList}).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if (res.data.status == 0) {
                                _this.showToster(res.data);
                            } else {
                                Swal.fire('Approved', 'Voucher successfully approved', 'success');
                                _this.getResults(_this.currentPageNo);
                                _this.deleteIdList = [];
                                
                            }
                        }
                    }).catch(function(error) {
                        _this.pageLoader = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'Whoops..!! something went wrong',
                            icon: 'error'
                        });
                    });
                }
            });

        },

        formPageData(url = false) {
            const _this = this;
            if (url) {
                var URL = _this.baseUrl + '/' + url;
            } else {
                var URL = _this.baseUrl + '/' + _this.dataUrl();
            }
            _this.pageLoader = true;
            axios.get(URL).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    _this.pageLoader = false;
                    if(res.data.status==0){
                        _this.showToster({
                            status: 0,
                            message: res.data.message
                        });
                    }else{
                        if (typeof res.data.formData !== 'undefined') {
                            _this.formData = res.data.formData;
                        }
                        _this.modalData = res.data;
                    }
                }
            })
            .catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
                
            })

        },
        storeWiseItem(store_id) {
            const _this = this;
            _this.gridData = {};
            var URL = _this.baseUrl + '/store-wise-item/' + store_id;
            _this.pageLoader = true;
            axios.get(URL).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    _this.pageLoader = false;
                    if (res.data.status == 0) {
                        _this.showToster(res.data);
                    } else {
                        _this.modalData.store_item_list = res.data;
                    }
                    
                }
            })
            .catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
                
            })

        },
        deleteCheck(state, id){
            const _this  = this;
            if(state==1){
                if(!_this.deleteIdList.includes(id)){
                    _this.deleteIdList.push(id);
                }
            }else{
                let index = _this.deleteIdList.indexOf(id);
                _this.deleteIdList.splice(index, 1);
            }

        },
        refCheck(state, data){
            const _this  = this;
            if(state==1){
                var checkExists =  this.refDataExist(data.id);
                if(checkExists.length==0){
                    _this.refDataList.push(data);
                }
            }else{
                let dataIndex = _this.refDataList.indexOf(data);
                _this.refDataList.splice(dataIndex, 1);
            }
        },
        addRefData(event){
            event.preventDefault();
            const _this = this;
            if(_this.refDataList.length>0){
                _this.refDataList.forEach(function(data, key){
                    var checkExists =  _this.voucherDetailsDataExist(data.reference_details_id);
                    if(checkExists.length==0){
                       _this.formData.voucherDetailsData.push(data); 
                    }else{
                        alert('Reference already added');
                    }
                    _this.formData.itemAdded='yes';
                });
                _this.refDataList = [];
                // uncheck all reference 
                _this.modalData.refItemList.forEach(function(data, key){
                    data.ref_check = 0;
                });
            }else{
                alert('No reference are selected');
            }

        },
        refDataExist(input){
            return this.refDataList.filter(function(row){
                return +row.id == +input; 
            });
        },
        voucherDetailsDataExist(input){
            return this.formData.voucherDetailsData.filter(function(row){
                return +row.reference_details_id == +input; 
            });
        },
        listDataMasterCheck(state){
            this.paginate_data.forEach(function(val,key){
                val.del_id=state;
            })
        },
        fractionQtyRateMultiply(itemInfo){
            var qtyUnitPrice  = +itemInfo.rate/itemInfo.round_of;
            var qtySplit  = String(itemInfo.qty).split(".");
            var roundQty = parseInt(qtySplit[0]);
            var fracQty = String(qtySplit[1]);
            var fractionQty = fracQty.padEnd(itemInfo.decimal_point_place, '0');
            fractionQty = parseInt(fractionQty);
            var amount  = +roundQty * +itemInfo.rate;
            var fractionPrice = +fractionQty * +qtyUnitPrice;
            amount += fractionPrice;
            return Math.round(amount);
        },
        selectSerial(type){
            const _this = this;            
            _this.formData.voucherDetailsData.forEach(function(data, key){
                if(data.item_id == _this.current_item_info.item_id){
                    data.row_style= (_this.serial_checkList.length>0)?'valid':'invalid';
                    data.serial_data = [..._this.serial_checkList];
                    // tooltiop html desing for serial info 
                    var tooltipHtml =  '<ul style="list-style:none">';
                    _this.serial_checkList.forEach(function(d, k){
                        tooltipHtml+= '<li>'+d.serial_code+'</li>';
                    });
                    tooltipHtml+='</ul>';
                    data.serial_html = tooltipHtml;
                    if(type=='purchase-receive'){
                        data.rec_qty = _this.serial_checkList.length;
                    }else if(type=='stock-in' || type=='stock-out'){
                        data.qty = _this.serial_checkList.length;
                    }else if(type=='issue-inventory'){
                        data.issue_qty = _this.serial_checkList.length;
                    }
                }
            });
            $('#serialForm').modal('hide');
        },
        serialCheck(event, state, data){
            const _this  = this;
            if(state==0){
                if(_this.current_item_info.avail_qty > _this.serial_checkList.length){
                    var checkExists =  this.serialCheckDataExist(data.id);
                    if(checkExists.length==0){
                        _this.serial_checkList.push(data);
                    }
                }else{
                    event.preventDefault();
                    alert('You can not add more then '+_this.current_item_info.avail_qty+' qty');
                    data.checked_id = 0;
                }
            }else{
                var checkExists =  this.serialCheckDataExist(data.id);
                if(checkExists.length>0){
                    var serial_check_list_new = this.serialCheckRemoveData(data.id);
                    _this.serial_checkList = [...serial_check_list_new];
                }
            }

        },
        slItemCheck(event, state, data){
            const _this  = this;
            if(state==0){
                if(_this.current_item_info.sl_avail_qty > _this.serial_checkList.length){
                    var checkExists =  this.serialCheckDataExist(data.id);
                    if(checkExists.length==0){
                        _this.serial_checkList.push(data);
                    }
                }else{
                    event.preventDefault();
                    alert('You can not add more then '+_this.current_item_info.sl_avail_qty+' qty');
                    data.checked_id = 0;
                }
            }else{
                var checkExists =  this.serialCheckDataExist(data.id);
                if(checkExists.length>0){
                    var serial_check_list_new = this.serialCheckRemoveData(data.id);
                    _this.serial_checkList = [...serial_check_list_new];
                }
            }

        },
        serialCheckDataExist(input){
            return this.serial_checkList.filter(function(row){
                return +row.id == +input; 
            });
        },
        serialCheckRemoveData(input){
            return this.serial_checkList.filter(function(row){
                return +row.id != +input; 
            });
        },
        openSerialModal(event, data, stock_type, sl_avail_qty='no'){
            const _this = this;
            event.preventDefault();
            $('#serialForm').modal({
                backdrop: 'static'
            });
            $('#serialForm').modal('show');
            var dataParams = {
                item_id: data.item_id,
                serial_data: data.serial_data,
                store_id:(_this.formData.store_id)?_this.formData.store_id:0,
                stock_type:stock_type
            };
            let URL = _this.baseUrl + '/purchase-receive-serial-data';
            _this.item_name = data.product_name;
            _this.serial_all_check = 0;
            _this.serial_checkList =  [...data.serial_data];
            _this.pageLoader = true;
            axios.post(URL,dataParams).then(res => {
                if (res.data.status == 'logout') {
                    window.location.href = res.data.url;
                } else {
                    _this.pageLoader = false;
                    if(res.data.status == 0){
                        _this.showToster({
                            status: 0,
                            message: res.data.message
                        });
                        _this.current_item_info={};
                        _this.serial_details = [];
                        _this.serial_all_select_text =  'Select';
                    }else{
                        _this.current_item_info = Object.assign({}, data);
                        _this.serial_details = res.data.serial_details;
                        if(sl_avail_qty=='no'){
                            _this.serial_all_select_text =  'Select Top '+_this.current_item_info.avail_qty+' qty';
                        }else{
                            _this.serial_all_select_text =  'Select Top '+_this.current_item_info.sl_avail_qty+' qty'; 
                        }
                        
                    }
                }
            })
            .catch(error => {
                _this.pageLoader = false;
                _this.showToster({
                    status: 0,
                    message: 'opps! something went wrong'
                });
            }); 
        },
        serialAllCheck(event, state){
            const _this=this;
            _this.serial_details.forEach(function(data, key){
                data.checked_id=0;
            });
            _this.serial_checkList = [];
            if(state==1){
                if(_this.current_item_info.avail_qty>0){
                    _this.serial_details.forEach(function(data, key){
                        if(_this.current_item_info.avail_qty > _this.serial_checkList.length){
                            _this.serial_checkList.push(data);
                            data.checked_id=1;  
                        }                 
                    });
                }
            }
        },
        slAllItemCheck(event, state){
            const _this=this;
            _this.serial_details.forEach(function(data, key){
                data.checked_id=0;
            });
            _this.serial_checkList = [];
            if(state==1){
                if(_this.current_item_info.sl_avail_qty>0){
                    _this.serial_details.forEach(function(data, key){
                        if(_this.current_item_info.sl_avail_qty > _this.serial_checkList.length){
                            _this.serial_checkList.push(data);
                            data.checked_id=1;  
                        }                 
                    });
                }
            }
        },
        assignValidationError: function(errors) {
            console.log('okay fire');
            const _this = this;
            $.each(errors, function(index, errorValue) {
                _this.$validator.errors.add({
                    id: index,
                    field: index,
                    name: index,
                    msg: errorValue[0],
                });
            })
        },
        showToster(info) {
            const _this = this;
            if (info.status == 1) {
                _this.$toastr.s({
                    title: 'Success!',
                    msg: info.message,
                    preventDuplicates: true,
                    position:'toast-top-center'
                });
                _this.errorsList = {};
            } else {
                if (typeof info.message === 'object') {
                    var msg = '<ul>';
                    info.message.forEach(function(v,k){
                        msg+='<li>'+v+'</li>';
                    });
                    msg+='</ul>';
                    //_this.errorsList = info.message;
                    //var msg = 'opps! something went wrong';
                } else {
                    var msg = info.message;
                }
                _this.$toastr.e({
                    title: 'Error!',
                    msg: msg,
                    preventDuplicates: true,
                    position:'toast-top-center'
                });
            }

        },
        onFileChange(e, fieldIndex) {
            const _this = this;
            var file = e.target.files[0];
            //var tempUrl = URL.createObjectURL(file);
            _this.$set(_this.imageData, fieldIndex, file);
            //_this.$set(_this.emptyImageData, fieldIndex, tempUrl);
        },
        removeFile(fieldIndex) {
            var _this = this;
            _this.imageData[fieldIndex] = '';
            _this.emptyImageData[fieldIndex] = _this.emptyImagePath;
        },
        sortingChanged(column) {
            var _this = this;
            if (_this.sort != column) {
                _this.order = 'asc';
            } else {
                if (_this.order == 'asc') _this.order = 'desc';
                else _this.order = 'asc';
            }
            _this.sort = column;
            _this.getResults();
        },
        filterSortingChanged(column, url = false) {
            var _this = this;
            if (_this.sort != column) {
                _this.order = 'asc';
            } else {
                if (_this.order == 'asc') _this.order = 'desc';
                else _this.order = 'asc';
            }
            _this.sort = column;
            if (url) {
                _this.getPaginateFilter(1, url);
            } else {
                _this.getPaginateFilter();
            }
        },
        getSortingClass(column) {
            if (this.sort == column) {
                return (this.order == 'asc') ? 'asc' : 'desc';
            }
        },
        getErrorClass(field) {
            return this.$validator.errors.has(field) ? 'is-invalid' : '';
        },
        onFileSelected: function(event, fieldIndex) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                this.imageData[fieldIndex] = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        formReset(hardReset = false) {
            const _this = this;
            _this.formData = {};
            _this.gridData={};
            _this.deleteIdList=[];
            Object.keys(_this.imageData).forEach((key) => {
                _this.imageData[key] = '';
            });
            Object.keys(_this.emptyImageData).forEach((key) => {
                _this.emptyImageData[key] = _this.emptyImagePath;
            });
            if (hardReset) { // hard reset is when all data is clear
                this.modalData = [];
            }
        },
        emptyDatePicker(objName,model1,model2){
            this[objName][model1] = null;
            this[objName][model2] = '';
        },
        getOnChangeData(obj) {
            const _this = this;
            if (typeof obj.params === 'object') {
                var params = {
                    params: obj.params
                };
            } else {
                var params = {};
            }
            if (obj.modelValue) {
                let URL = _this.baseUrl + '/' + obj.dataUrl;
                axios.get(URL, params).then(response => {
                    if (response.data.status == 'logout') {
                        window.location.href = response.data.url;
                    } else {
                        if (obj.dataObj !== undefined) {
                            _this.$set(_this[obj.dataObj], obj.dataModel, response.data);
                        } else {
                            _this.$set(_this, obj.dataModel, response.data);
                        }
                    }
                }).catch(function(error) {
                    _this.showToster({
                        status: 0,
                        message: 'opps! something went wrong'
                    });
                });
            } else {
                if (obj.dataObj !== undefined) {
                    _this.$set(_this[obj.dataObj], obj.dataModel, {});
                } else {
                    _this.$set(_this, obj.dataModel, {});
                }
            }
        },
        viewReport(reportData, except = [], rUrl = false) {
            const _this = this;
            _this.$validator.validate().then(valid => {
                if (valid) {
                    var urlParam = "";
                    var Url = "";
                    if (typeof reportData != 'undefined') {
                        if (Object.keys(reportData).length > 0) {
                            for (let key in reportData) {
                                if (!except.includes(key)) {
                                    urlParam += ((urlParam == "") ? "?" : "&") + key + "=" + reportData[key];
                                }
                            }
                        }
                    }
                    if (rUrl) {
                        Url = rUrl;
                    } else {
                        Url = _this.$route.meta.reportUrl;
                    }
                    var width = $(document).width();
                    var height = $(document).height();
                    var myWindow = window.open(_this.baseUrl + '/' + Url + urlParam, "", "width=" + width + ",height=" + height);
                }
            });
        },
        currentDate() {
            var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            var current_date = [year, month, day].join('-');
            return current_date;
        },
        to_fixed(value, decimal_point_place){
            return parseFloat(value).toFixed(decimal_point_place);
        },
        popoverShow() {
            $('[data-toggle="popover"]').popover();
        },
        initStatus() {
            this.formData.status = 1;
        },
        dataUrl: function() {
            return dataUrl;
            //return this.$route.meta.dataUrl;
        },
        isEmpty(obj) {
            return Object.keys(obj).length === 0;
        },
        isObject(v) {
            return (typeof v === 'object') ? true : false;
        },
        getImage: function(path) {
            return baseUrl + '/' + path;
        },
        getEmptyImage(index) {
            return this.emptyImageData = this.emptyImagePath;
        },
        getActiveClass(route) {
            return (this.$route.path == route) ? 'active open' : '';
        },
        isFloat(n){
            return Number(n) % 1 !== 0;
        },
        inWords (num) {
            var a = this.numberWords.a;
            var b = this.numberWords.b;

            if ((num = num.toString()).length > 9) return 'overflow';
            n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return; var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'taka only. ' : '';
            return str;
        },
        print (url){
            const _this = this;
            const URL = _this.baseUrl + '/print/' + url;
            window.open(URL);
        }
    }
}
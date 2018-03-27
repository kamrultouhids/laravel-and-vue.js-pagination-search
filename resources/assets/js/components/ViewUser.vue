<template>
    <div >
        <div class="row ">
            <div class="col-md-offset-8 col-md-4">
                <input type="text" class="form-control" v-model="filter" placeholder="search" @keyup="search()">
            </div>
        </div>
        <table class="table loader-form" v-cloak >
            <thead>
                <tr class="tr">
                    <th>S/L</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="resultData.data !=''" >
                <tr class="tr" v-for="(value,index) in resultData.data" >
                    <td>{{ index + 1 }}</td>
                    <td>{{ value.name }}</td>
                    <td>{{ value.email }}</td>
                    <td>
                        <a href="" class="btn btn-success btn-sm" @click.prevent="openUpdateModal(value.id)">
                            <span class="glyphicon glyphicon-pencil"></span> Update
                        </a>
                        <a v-on:click="deleteUser(value.id, index)"  class="btn btn-danger btn-sm">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                        </a>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="2">No data have found !</td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="text-center col-md-12" v-if="resultData.last_page > 1">
                <ul class="pagination">
                    <li :class="[ ((resultData.current_page == 1) ? 'disabled' : '') ]">
                         <a :href="'?page='+resultData.current_page" @click.prevent="pageClicked(resultData.current_page-1)" aria-label="Previous" v-if="resultData.current_page != 1">
                             <span aria-hidden="true">«</span>
                         </a>
                        <a v-else>
                            <span  aria-hidden="true">«</span>
                        </a>
                    </li>
                    <li v-for="pageNo in range(paginateLoop, numberOfPage)"
                        :class="[ ((resultData.current_page == pageNo) ? 'active' : '') ]">
                        <a :href="'?page='+pageNo" @click.prevent="pageClicked(pageNo)">{{ pageNo }}</a>
                    </li>
                    <li :class="[ ((resultData.current_page == resultData.last_page) ? 'disabled' : '') ]" >
                        <a  :href="'?page='+resultData.current_page" @click.prevent="pageClicked(resultData.current_page+1)" aria-label="Next" v-if="resultData.current_page != resultData.last_page">
                            <span aria-hidden="true">»</span>
                        </a>
                        <a v-else>
                            <span  aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <update-user></update-user>
    </div>
</template>

<script>
    import { EventBus } from '../vue-assets';
    import UpdateUser from './UpdateUser.vue';

    export default {
        components: {
            'update-user': UpdateUser
        },
        data: function(){
            return {
                resultData: {},
                filter: ''
            };
        },
        methods: {
            index: function(pageNo,filter){
                if(pageNo){
                    pageNo = pageNo;
                }else{
                    pageNo = this.resultData.current_page;
                }
                if(filter){
                    filter = filter;
                }else{
                    filter = '';
                }

                axios.get("/users?page="+pageNo+"&filter="+filter).then((res) => {
                    this.resultData = res.data;
                });

            },

            deleteUser: function (id, index) {
                var vueThis = this;
                if (confirm("Do you really want to delete it?")) {
                    axios.delete('/users/' + id).then((response) => {
                        if(response.data.status == 'success'){
                            EventBus.$emit('user-created', response.data);
                            this.showMassage(response.data);
                        }else{
                            this.showMassage(response.data);
                        }
                    }).catch(error => {
                        this.showMassage(response.data);
                    });
                }
            },

            showMassage(data) {
                if (data.status == 'success') {
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                } else {
                    toastr.error(data.message, 'Error Alert', {timeOut: 5000});
                }
            },

            openUpdateModal(id){
                EventBus.$emit('update-buton-clicked', id);
            },

            pageClicked(pageNo){
                var vm = this;
                window.history.pushState({}, null, "?page="+pageNo+"&filter="+vm.filter);
                vm.index(pageNo,vm.filter);
            },

            search(){
                var vm = this;
                window.history.pushState({}, null, "?page="+1+"&filter="+this.filter);
                vm.index(1,vm.filter);
            },

            range(start, count) {
                return Array.apply(0, Array(count))
                    .map(function (element, index){
                        return index + start;
                    });
            },

            getQueryParameters(str){
                if(str){
                    return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
                }else{
                    return {};
                }
            }
        },

        created() {
           var _this = this;
            let queryParam = this.getQueryParameters(location.search);
            let pageNo = (queryParam.hasOwnProperty('page')) ? queryParam.page : 1;
            let filter = (queryParam.hasOwnProperty('filter')) ? queryParam.filter : '';
            this.filter = filter;
            _this.index(pageNo, filter);

            EventBus.$on('user-created', function () {
                window.history.pushState({}, null, location.pathname);
                _this.index(1);
            });
        },

        computed: {
            paginateLoop(){
                let resultData = this.resultData;

                if(resultData.last_page > 11){
                    if((resultData.last_page - 5) <= resultData.current_page){
                        return resultData.last_page - 10;
                    }

                    if(resultData.current_page > 6){
                        return resultData.current_page - 5;
                    }
                }
                return 1;
            },

            numberOfPage(){
                if(this.resultData.last_page < 11){
                    return this.resultData.last_page;
                }else{
                    return 11;
                }
            }
        }
    };
</script>

<template>
    <div class="modal fade" id="update-item" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Update User</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <ul>
                            <li v-for="error in errors">{{ error[0] }}</li>
                        </ul>
                    </div>
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="update(form.id)" class="loader-form">
                        <div class="form-group">
                            <label>Name<span class="requiredField">*</span></label>
                            <input type="text" class="form-control" v-model="form.name"  placeholder="Enter Your Name">
                        </div>

                        <div class="form-group">
                            <label>Email Address<span class="requiredField">*</span></label>
                            <input type="email" class="form-control" v-model="form.email" placeholder="Enter Your Email"/>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-ok"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../vue-assets';

    export default {
        name: 'update-user',
        data: function(){
            return {
                form: {
                    id: '',
                    name: '',
                    email: ''
                },
                errors: null
            };
        },

        methods: {
            update(id){
                var vm = this;
                axios.put(base_url+'users/'+id, this.form).then((response) => {
                    $('#update-item').modal('hide');
                    EventBus.$emit('user-created', 1);
                    this.showMassage(response.data);
                }).catch(error => {
                    if(error.response){
                        this.errors = error.response.data.errors;
                    }else{
                        console.log(error);
                    }
                    vm.loading = false;
                });
            },

            showMassage(data){
                if(data.status == 'success'){
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                }else{
                    toastr.error(data.message, 'Error Alert', {timeOut: 5000});
                }
            },

        },

        mounted(){
            var vm = this;
            EventBus.$on('update-buton-clicked', function(id){
                $('#update-item').modal('show');
                axios.get(base_url+'users/'+id).then((response) => {
                    vm.form = response.data.data;
                });
            });

            $('#update-item').on('hidden.bs.modal', function(){
                vm.errors = null;
                vm.form = {'name':'','email':''};
            });
        }
    };
</script>
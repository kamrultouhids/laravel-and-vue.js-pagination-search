<template>
    <div>
        <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel"> Create User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors">
                            <ul>
                                <li v-for="error in errors">{{ error[0] }}</li>
                            </ul>
                        </div>
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="store">
                            <div class="form-group">
                                <label for="lable">Name<span class="requiredField">*</span></label>
                                <input type="text" class="form-control" v-model="form.name"  placeholder="Enter Your Name">
                            </div>

                            <div class="form-group">
                                <label for="lable">Email Address<span class="requiredField">*</span></label>
                                <input type="email" class="form-control" v-model="form.email" placeholder="Enter Your Email"/>
                            </div>

                            <div class="form-group">
                                <label for="lable">Password<span class="requiredField">*</span></label>
                                <input type="password" class="form-control" v-model="form.password" placeholder="Enter Your Password"/>
                            </div>

                            <div class="form-group">
                                <label for="lable">Confirm Password<span class="requiredField">*</span></label>
                                <input type="password" class="form-control" v-model="form.password_confirmation" placeholder="Enter Confirm Password"/>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-ok"></i> Save</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../vue-assets';

    export default {
        data: function(){
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errors: null,
            };
        },

        methods: {
            store: function () {
                axios.post(base_url+'users', this.form).then((response) => {
                    $('#create-item').modal('hide');
                    this.form = {'name':'','email':'','password':'','password_confirmation':''};
                    this.errors = null;
                    this.showMassage(response.data);
                    EventBus.$emit('user-created', response.data);
                }).catch(error => {
                    if(error.response){
                        this.errors = error.response.data.errors;
                    }
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
            $('#create-item').on('hidden.bs.modal', function(){
                vm.errors = null;
                vm.form = {'name':'','email':'','password':'','password_confirmation':''};
            });
        }
    };
</script>
<template>
    <form action="#">
        <div class="form-group" :class="{'has-error': errors.name}">
            <label>Nombre:</label>
            <input type="text" class="form-control" placeholder="Nombre del rol" v-model="role.name" v-on:keyup="resetErrors('name')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                <span ref="noerrors.name" v-else class="help-block">Escribe el nombre del rol</span>
            </transition>
        </div>
        <div class="text-right">
            <a class="btn" href="/roles"><i class=" icon-arrow-left15 left"></i> Regresar</a>
            <button v-if="role.id>0" @click.prevent="updateRol" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
            </button>
            <button v-else @click.prevent="createRol" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
            <button v-if="role.id>0" @click.prevent="deleteRol" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            role_id: {
                type: Number,
                default: 0
            }
        },
        data(){
            return {
                role: {
                    id: this.role_id,
                    name: ''
                },
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                    'folder': 'roles/' + this.role_id
                }
            }
        },
        mounted() {
            if (this.role_id>0) {
                axios.get('/roles/' + this.role_id).then(
                    ({data}) => {
                        if (data.role) {
                            this.role = data.role;
                        }
                    }
                ).catch();
            }
        },
        methods: {
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createRol(){
                window.vm.active++;
                axios.post('/roles', this.role).then(
                    ({data}) => {
                        if (data.role) {
                            this.role = data.role;
                            this.adittionaldata.folder = 'roles/' + this.role.id;
                        }
                        if (data.message) new PNotify({
                            text: data.message,
                            addclass: 'bg-' + data.status,
                            type: data.status,
                            animation: 'fade',
                            delay: 2000
                        });
                        window.vm.active--;
                    }
                ).catch(function (error) {
                    window.vm.active--;
                    if (error.response) {
                        if (error.response.status == 422) {
                            var data = error.response.data;
                            this.errors = data;
                        } else {
                            console.log(error.response.status);
                        }
                    } else {
                        console.log('Error', error.message);
                    }
                }.bind(this));
            },
            updateRol(){
                window.vm.active++;
                axios.put('/roles/' + this.role.id, this.role).then(
                    ({data}) => {
                        if (data.role) this.role = data.role;
                        if (data.message) new PNotify({
                            text: data.message,
                            addclass: 'bg-' + data.status,
                            type: data.status,
                            animation: 'fade',
                            delay: 2000
                        });
                        window.vm.active--;
                    }
                ).catch(function (error) {
                    window.vm.active--;
                    if (error.response) {
                        if (error.response.status == 422) {
                            var data = error.response.data;
                            this.errors = data;
                        } else {
                            console.log(error.response.status);
                        }
                    } else {
                        console.log('Error', error.message);
                    }
                }.bind(this));

            },
            deleteRol(){
                if (confirm('¿Estás seguro que quieres eliminar este registro?')) {
                    window.vm.active++;
                    axios.delete('/roles/' + this.role.id).then(
                        ({data}) => {
                            if (data.message) new PNotify({
                                text: data.message,
                                addclass: 'bg-' + data.status,
                                type: data.status,
                                animation: 'fade',
                                delay: 2000
                            });
                            window.vm.active--;
                            if (data.status=='success') {
                                document.location.href = '/roles';
                            }
                        }
                    ).catch(function (error) {
                        window.vm.active--;
                        if (error.response) {
                            if (error.response.status == 422) {
                                var data = error.response.data;
                                this.errors = data;
                            } else {
                                console.log(error.response.status);
                            }
                        } else {
                            console.log('Error', error.message);
                        }
                    }.bind(this));
                }
            }
        }
    }
</script>

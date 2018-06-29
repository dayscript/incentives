<template>
    <form action="#">
        <div class="form-group" :class="{'has-error': errors.name}">
            <label>Nombre:</label>
            <input type="text" class="form-control" placeholder="Nombre del indicador" v-model="indicator.name" v-on:keyup="resetErrors('name')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                <span ref="noerrors.name" v-else class="help-block">Escribe el indicador</span>
            </transition>
        </div>
        <div class="form-group">
          <label>Cliente:</label>
          <select name="client_id" id="client_id" class="select" v-model="indicator.client_id">
            <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>Tipo:</label>
          <select name="type_id" id="type_id" class="select" v-model="indicator.type_id">
            <option v-for="type in types" :value="type.id">{{ type.name }}</option>
          </select>
        </div>
        <div class="text-right">
            <a class="btn" href="/indicator"><i class=" icon-arrow-left15 left"></i> Regresar</a>
            <button v-if="indicator.id>0" @click.prevent="updateRol" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
            </button>
            <button v-else @click.prevent="createRol" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
            <button v-if="indicator.id>0" @click.prevent="deleteRol" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            indicator_id: {
                type: Number,
                default: 0
            }
        },
        data(){
            return {
                indicator: {
                    id: this.indicator_id,
                    name: '', 
                    client_id: null,
                    type_id:null
                },
                clients: [],
                types: [],
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                    'folder': 'indicator/' + this.indicator_id
                }
            }
        },
        mounted() {
            if (this.indicator_id>0) {
                axios.get('/indicator/' + this.indicator_id).then(
                    ({data}) => {
                        if (data.indicator) {
                            this.indicator = data.indicator;
                        }
                    }
                ).catch();
            }
            axios.get('/api/clients').then(
              ({data}) => {
                  if (data.clients) {
                      this.clients = data.clients;
                  }
              }
            ).catch();
            axios.get('/api/types').then(
              ({data}) => {
                  if (data.types) {
                      this.types = data.types;
                  }
              }
            ).catch();
            setTimeout(function () {
                $('.select').select2();
            }, 300);
        },
        methods: {
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createRol(){
                window.vm.active++;
                this.indicator.client_id = $('#client_id').val();
                this.indicator.type_id = $('#type_id').val();
                axios.post('/indicator', this.indicator).then(
                    ({data}) => {
                        if (data.indicator) {
                            this.indicator = data.indicator;
                            this.adittionaldata.folder = 'indicator/' + this.indicator.id;
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
                this.indicator.client_id = $('#client_id').val();
                this.indicator.type_id = $('#type_id').val();
                axios.put('/indicator/' + this.indicator.id, this.indicator).then(
                    ({data}) => {
                        if (data.indicator) this.indicator = data.indicator;
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
                    axios.delete('/indicator/' + this.indicator.id).then(
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
                                document.location.href = '/indicator';
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

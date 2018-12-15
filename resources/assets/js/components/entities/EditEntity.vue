<template>
  <form action="#">
    <div class="form-group">
      <label>Cliente:</label>
      <select name="client_id" id="client_id" class="select" v-model="entity.client_id">
        <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
      </select>
    </div>

    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Identificación:</label>
      <input type="text" class="form-control" placeholder="Numero de identificación" v-model="entity.identification" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Numero de identificación</span>
      </transition>
    </div>

    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Nombre:</label>
      <input type="text" class="form-control" placeholder="Nombre de la meta" v-model="entity.name" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Escribe el nombre de la meta</span>
      </transition>
    </div>


      <div class="text-right">
      <a class="btn" href="/entities"><i class=" icon-arrow-left15 left"></i> Regresar</a>
      <button v-if="entity.id>0" @click.prevent="updateGoal" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
      </button>
      <button v-else @click.prevent="createGoal" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
      <button v-if="entity.id>0" @click.prevent="deleteGoal" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
      </button>
    </div>
  </form>
</template>

<script>
    export default {
        props: {
            entity_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                entity: {
                    id: this.entity_id,
                    identification:'',
                    name: '',
                    totalpoints:'',
                    points_values:'',
                    invoices:'',
                    redemptions:'',
                    entity_goals:'',
                    entity_information:'',
                    description: '',
                    modifier: '',
                    weight: '100',
                    client_id: null
                },
                clients: [],
                modifiers: [

                ],
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                }
            }
        },
        mounted() {
            if (this.entity_id > 0) {
                axios.get('/entities/' + this.entity_id).then(
                  ({data}) => {

                      if (data) {
                          this.entity = data;
                          console.log(this.entity)
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

            setTimeout(function () {
                $('.select').select2();
            }, 300);


        },
        methods: {
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createGoal(){
                window.vm.active++;
                axios.post('/entities', this.entity).then(
                  ({data}) => {
                      if (data.entity) this.entity = data.entity;
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
            updateGoal(){
                window.vm.active++;
                this.entity.client_id = $('#client_id').val();
                axios.put('/entities/' + this.entity.id, this.entity).then(
                  ({data}) => {
                      if (data.entity) this.entity = data.entity;
                      setTimeout(function () {
                          $('.select').select2();
                      }, 300);
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
            deleteGoal(){
                if (confirm('¿Estás seguro que quieres eliminar esta meta?')) {
                    window.vm.active++;
                    axios.delete('/entities/' + this.entity.id).then(
                      ({data}) => {
                          if (data.message) new PNotify({
                              text: data.message,
                              addclass: 'bg-' + data.status,
                              type: data.status,
                              animation: 'fade',
                              delay: 2000
                          });
                          window.vm.active--;
                          if (data.status == 'success') {
                              document.location.href = '/entities';
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

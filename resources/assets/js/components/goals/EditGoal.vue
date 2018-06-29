<template>
  <form action="#">
    <div class="form-group">
      <label>Cliente:</label>
      <select name="client_id" id="client_id" class="select" v-model="goal.client_id">
        <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
      </select>
    </div>
    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Nombre:</label>
      <input type="text" class="form-control" placeholder="Nombre de la meta" v-model="goal.name" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Escribe el nombre de la meta</span>
      </transition>
    </div>
    <div class="form-group" :class="{'has-error': errors.description}">
      <label>Descripción:</label>
      <input type="text" class="form-control" placeholder="Descripción de la meta" v-model="goal.description"
             v-on:keyup="resetErrors('description')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.description" v-if="errors.description" class="help-block text-danger">{{ errors.description[0] }}</span>
        <span ref="noerrors.description" v-else class="help-block">Escribe una descripción sobre el contenido de esta meta</span>
      </transition>
    </div>
    <div class="form-group">
      <label>Modificador de cálculo:</label>
      <select name="modifier" id="modifier" class="select" v-model="goal.modifier">
        <option v-for="modif in modifiers" :value="modif.key">{{ modif.label }}</option>
      </select>
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.modifier" v-if="errors.modifier" class="help-block text-danger">{{ errors.modifier[0] }}</span>
        <span ref="noerrors.modifier" v-else class="help-block">Un modificador permite alterar el resultado del cálculo de cumplimiento de la meta</span>
      </transition>
    </div>
    <div class="form-group" :class="{'has-error': errors.weight}">
      <label>Peso en el indicador:</label>
      <input type="number" class="form-control" placeholder="100" v-model="goal.weight" v-on:keyup="resetErrors('weight')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.weight" v-if="errors.weight" class="help-block text-danger">{{ errors.weight[0] }}</span>
        <span ref="noerrors.weight" v-else class="help-block">Peso en el indicador</span>
      </transition>
    </div>
    <div class="form-group" :class="{'has-error': errors.date_start}">
      <label>Fecha Inicio:</label>
      <input type="date" class="form-control" v-model="goal.date_start" v-on:keyup="resetErrors('date_start')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.date_start" v-if="errors.date_start" class="help-block text-danger">{{ errors.date_start[0] }}</span>
      </transition>
    </div>
    <div class="form-group" :class="{'has-error': errors.date_end}">
      <label>Fecha Fin:</label>
      <input type="date" class="form-control" v-model="goal.date_end" v-on:keyup="resetErrors('date_end')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.date_end" v-if="errors.date_end" class="help-block text-danger">{{ errors.date_end[0] }}</span>
      </transition>
    </div>
    <div class="form-group">
      <label>Indicador:</label>
      <select name="indicator_id" id="indicator_id" class="select" v-model="goal.indicator_id">
        <option v-for="indicator in indicators" :value="indicator.id">{{ indicator.name }}</option>
      </select>
    </div>
    <div class="form-group">
      <label>Rol:</label>
      <select name="rol_id" id="rol_id" class="select" v-model="goal.rol_id">
        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
      </select>
    </div>
    <div class="form-group" :class="{'has-error': errors.point}">
      <label>Puntos:</label>
      <input type="number" class="form-control" placeholder="100" v-model="goal.point" v-on:keyup="resetErrors('point')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.point" v-if="errors.point" class="help-block text-danger">{{ errors.point[0] }}</span>
        <span ref="noerrors.point" v-else class="help-block">Puntos del indicador</span>
      </transition>
    </div>
    <div class="text-right">
      <a class="btn" href="/goals"><i class=" icon-arrow-left15 left"></i> Regresar</a>
      <button v-if="goal.id>0" @click.prevent="updateGoal" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
      </button>
      <button v-else @click.prevent="createGoal" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
      <button v-if="goal.id>0" @click.prevent="deleteGoal" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
      </button>
    </div>
  </form>
</template>

<script>
    export default {
        props: {
            goal_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                goal: {
                    id: this.goal_id,
                    name: '',
                    description: '',
                    modifier: '',
                    weight: '100',
                    client_id: null,
                    indicator_id: null,
                    date_start: '',
                    date_end: '',
                    rol_id: null,
                    point: ''
                },
                clients: [],
                indicators: [],
                roles: [],
                modifiers: [
                    {key:'none',
                    label:'Ninguno'},
                    {
                        key: 'modifier1',
                        label: 'Rango 0% - 80% - 100% - 120%'
                    },
                    {
                        key: 'modifier2',
                        label: 'Rango 0% - 80% - 100%'
                    },
                    {
                        key: 'modifier3',
                        label: 'Rango 0% - 80% - 90% - 100%'
                    },
                    {
                        key: 'modifier4',
                        label: 'Rango 0% - 90% - 100%'
                    },
                    {
                        key: 'modifier5',
                        label: 'Rango 0% - 120%, 125%'
                    },
                    {
                        key: 'modifier6',
                        label: 'Rango 0% - 100%, Max 100%'
                    },
                    {
                        key: 'modifier7',
                        label: 'Min 0%'
                    },
                    {
                        key: 'modifier8',
                        label: '0% - 120%, > 120%, +5% '
                    }


                ],
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                }
            }
        },
        mounted() {
            if (this.goal_id > 0) {
                axios.get('/goals/' + this.goal_id).then(
                  ({data}) => {
                      if (data.goal) {
                          this.goal = data.goal;
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
            axios.get('/api/indicators').then(
              ({data}) => {
                  if (data.indicators) {
                      this.indicators = data.indicators;
                  }
              }
            ).catch();
            axios.get('/api/roles').then(
              ({data}) => {
                  if (data.roles) {
                      this.roles = data.roles;
                  }
              }
            ).catch();
            /*setTimeout(function () {
                $('.select').select2();
            }, 300);*/
        },
        methods: {
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createGoal(){
                window.vm.active++;
                axios.post('/goals', this.goal).then(
                  ({data}) => {
                      if (data.goal) {
                        this.goal = data.goal;
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
            updateGoal(){
                window.vm.active++;
                axios.put('/goals/' + this.goal.id, this.goal).then(
                  ({data}) => {
                      if (data.goal) this.goal = data.goal;
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
                    axios.delete('/goals/' + this.goal.id).then(
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
                              document.location.href = '/goals';
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

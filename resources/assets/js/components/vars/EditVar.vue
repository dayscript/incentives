<template>
  <form action="#">
    <div class="form-group">
      <label>Cliente:</label>
      <select name="client_id" id="client_id" class="select" v-model="vars.client_id">
        <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
      </select>
    </div>
    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Nombre:</label>
      <input type="text" class="form-control" placeholder="Nombre de la regla" v-model="vars.name" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Escribe el nombre de la regla de acumulación</span>
      </transition>
    </div>

    <div class="form-group">
      <label>Tipo de Variable</label>
      <select name="type" id="type"  v-model="vars.type">
        <option v-for="type in types" :value="type.key">{{ type.label }}</option>
      </select>
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.modifier" v-if="errors.modifier" class="help-block text-danger">{{ errors.modifier[0] }}</span>
        <span ref="noerrors.modifier" v-else class="help-block">Un modificador permite alterar el resultado del cálculo de puntos</span>
      </transition>
    </div>

    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Descripción:</label>
      <input type="text" class="form-control" placeholder="Descripción de variable" v-model="vars.description" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.description" class="help-block text-danger">{{ errors.description[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Descripcion de la regla</span>
      </transition>
    </div>

    <div class="form-group" :class="{'has-error': errors.value}">
      <label>Valor:</label>
      <input type="text" class="form-control" placeholder="Valor de la variable" v-model="vars.value" v-on:keyup="resetErrors('value')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.value" v-if="errors.value" class="help-block text-danger">{{ errors.value[0] }}</span>
        <span ref="noerrors.value" v-else class="help-block">Escribe el valor de la variable de acumulación</span>
      </transition>
    </div>

    <!-- Falta validar que se muestre o no dependiendo del tipo de var-->
    <div class="form-group" v-if="vars.type != 'aa' ">
      <label>Var Uno:</label>
      <select name="vars_one_id" id="vars_one_id" class="select" v-model="vars.vars_one_id">
        <option v-for="variable in variables" :value="variable.id">{{ variable.name }}</option>
      </select>
      <span ref="errors.value" v-if="errors.value" class="help-block text-danger">{{ errors.value[0] }}</span>
      <span ref="noerrors.value" v-else class="help-block">Buscar una variable de acumulación</span>
    </div>

    <div class="form-group">
      <label>Operador:</label>
      <select name="type" id="type"  v-model="vars.operator">
        <option v-for="operator in operators" :value="operator.key">{{ operator.label }}</option>
      </select>
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.modifier" v-if="errors.modifier" class="help-block text-danger">{{ errors.modifier[0] }}</span>
        <span ref="noerrors.modifier" v-else class="help-block">El operador permite computar dos variables para obtener un resultado adicional</span>
      </transition>
    </div>

    <div class="form-group" v-if="vars.type != 'aa'">
      <label>Var Dos</label>
      <select name="vars_two_id" id="vars_two_id" class="select" v-model="vars.vars_two_id">
        <option v-for="variable in variables" :value="variable.id">{{ variable.name }}</option>
      </select>
      <span ref="errors.value" v-if="errors.value" class="help-block text-danger">{{ errors.value[0] }}</span>
      <span ref="noerrors.value" v-else class="help-block">Buscar una variable de acumulación</span>

    </div>
    <!-- Falta validar que se muestre o no dependiendo del tipo de var-->

    <div class="text-right">
      <a class="btn" href="/vars"><i class=" icon-arrow-left15 left"></i> Regresar</a>
      <button v-if="vars.id>0" @click.prevent="updateRule" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
      </button>
      <button v-else @click.prevent="createRule" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
      <button v-if="vars.id>0" @click.prevent="deleteRule" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
      </button>
    </div>
  </form>
</template>

<script>
    export default {
        props: {
            vars_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                vars: {
                    id: this.vars_id,
                    name: '',
                    client_id: 1,
                    type:'',
                    value:'',
                    vars_one_id:'',
                    vars_two_id:'',
                    description:'',
                    machine_name:'',
                    operator:''
                },
                clients: [],
                variables:[],
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,

                },
                types:[
                  { key:'single' , label: 'Simple'},
                  { key:'constant' , label: 'Constante'},
                  { key:'bool' , label: 'Verdadero/Falso'},
                  { key:'percentage' , label: 'Porcentaje'},
                  { key:'assignment' , label: 'Asignación'},
                  { key:'composite' , label: 'Compuesta'}
                ],
                operators:[
                  {key:'sum' , label:'Suma'},
                  {key:'rest', label: 'Resta'},
                  {key:'mult', label: 'Multiplicación'},
                  {key:'div' , label:'División'}
                ]
            }
        },
        mounted() {

            if (this.vars_id > 0) {
                axios.get('/vars/' + this.vars_id).then(
                  ({data}) => {
                      if (data.vars) {
                          this.vars = data.vars;
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

            axios.get('/api/vars').then(
              ({data}) => {
                  if (data.variables) {
                      this.variables = data.variables;
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
            createRule(){
                window.vm.active++;
                axios.post('/vars', this.vars).then(
                  ({data}) => {
                      if (data.vars) this.vars = data.vars;
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
            updateRule(){
                window.vm.active++;
                this.vars.client_id = $('#client_id').val();
                this.vars.vars_one_id = $('#vars_one_id').val();
                this.vars.vars_two_id = $('#vars_two_id').val();

                axios.put('/vars/' + this.vars.id, this.vars).then(
                  ({data}) => {
                      if (data.vars) this.vars = data.vars;
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
            deleteRule(){
                if (confirm('¿Estás seguro que quieres eliminar esta regla?')) {
                    window.vm.active++;
                    axios.delete('/vars/' + this.vars.id).then(
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
                              document.location.href = '/vars';
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

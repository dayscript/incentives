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
        <span ref="noerrors.name" v-else class="help-block">Escribe el nombre la plantilla</span>
      </transition>
    </div>

    <div class="form-group" :class="{'has-error': errors.name}">
      <label>Descripción:</label>
      <input type="text" class="form-control" placeholder="Descripción de variable" v-model="vars.description" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.description" class="help-block text-danger">{{ errors.description[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Descripcion de la plantilla</span>
      </transition>
    </div>


    <div class="form-group">
      <label> Variables: </label>
        <select v-bind:id="'var'" v-bind:name="'var'" class="" v-model="select_var">
          <option v-for="variable in variables" :value="variable.id">{{ variable.name }}</option>
        </select>
        <a class="button"  @click="addMore()" >Agregar</a>
        <span ref="errors.value" v-if="errors.value" class="help-block text-danger">{{ errors.value[0] }}</span>
        <span ref="noerrors.value" v-else class="help-block">Agregar una variable</span>
    </div>

    <br>
    <div class="form-group">
      <label> Variables asignadas: </label><br>
        <span v-for="(item,key) in this.vars.vars">{{item.name}} <a @click="deleteOne(item)">eliminar</a>  <br> </span>
    </div>


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
            template_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                vars: {
                    id: this.template_id,
                    name: '',
                    client_id: 1,
                    description:'',
                    machine_name:'',
                    vars:[],
                },
                clients: [],
                variables:[],
                select_var:null,
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,

                },
            }
        },
        mounted() {
            if (this.template_id > 0) {
                axios.get('/templates/' + this.template_id).then(
                  ({data}) => {

                      if (data.templates) {
                          this.vars = data.templates;
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
                axios.post('/templates', this.vars).then(
                  ({data}) => {
                      if (data.templates) this.vars = data.templates;
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
                axios.put('/templates/' + this.vars.id, this.vars).then(
                  ({data}) => {
                      if (data.templates) this.vars = data.templates;
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
                    axios.delete('/templates/' + this.vars.id).then(
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
            },

            addMore(){
              var match;
              for( var key in this.variables){
                 if(this.variables[key].id == this.select_var){
                    match = this.variables[key];
                 }
              }
              this.vars.vars.push( match );
              this.select_var = null;
            },


            deleteOne(item){
              var index = this.vars.vars.indexOf(item);
              this.vars.vars.splice(index,1);
            }
        }
    }
</script>

<template>
  <div class="col-md-12">
    <div class="row">
      <div class="points" id="points">
        <div class="card">
          <div class="card-header header-elements-inline">
            <h5 class="card-title"></h5>
            <div class="header-elements">
              <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="reload"></a>
                <a class="list-icons-item" data-action="remove"></a>
              </div>
            </div>
          </div>
          <div class="card-body">
          </div>
          <div class="table-responsive">
            <table class="table table-xs">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Valor</th>
                  <th>Valor en pesos</th>
                  <th>Token</th>
                  <th>Opciones</th>

                </tr>
              </thead>
              <tbody>
                <tr v-for="(points,key) in entity.redemptions">
                  <td>{{ key }}</td>
                  <td>{{ points.value }}</td>
                  <td>$ {{ points.value * 50 }}</td>
                  <td>{{ points.token }}</td>
                  <td>{{ points.created_at }}</td>

                  <td> <button @click.prevent="deleteRedemption(points.id)" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i></button> </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 show-grid">
      <span class="badge bg-success-400">Puntos Totales: {{ entity.totalpoints }}</span>
      <!-- <input type="text" class="form-control" placeholder="Nombre de la meta" v-model="entity.totalpoints" v-on:keyup="resetErrors('name')">
      <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
        <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
        <span ref="noerrors.name" v-else class="help-block">Puntos Totales</span>
      </transition> -->
    </div>
    <div class="row show-grid">
      <div class="row">
        <h6>AGREGAR UNO</h6>
          <form action="#">
            <div class="form-group">
                <label for="">Escriba el valor que desea agregar</label>
                <input type="hidden" name="entity_id" class="form-control" v-model="entity.id">

                <input type="text" name="value" class="form-control" v-model="redemptionValue">
            </div>
            <div class="form-group">
              <button  @click.prevent="createRedemption" class="btn btn-primary">Crear <i class="icon-paperplane ml-2"></i></button>
              </button>
            </div>
          </form>
      </div>
    </div>
  </div>
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
                redemptionValue: '',
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

        },
        methods: {
            createRedemption(){
              axios.post('/api/redemptions',{ entity_id:this.entity.id,value:this.redemptionValue}).then(
                ({data}) => {
                  console.log(data);
                }

              ).catch(function (error) {
                  console.log(error);
              }.bind(this));

            },

            deleteRedemption(id){
              window.vm.active++;
              axios.post('/api/redemptions/delete',{id:id}).then(
                data => {
                    if (data.status) new PNotify({
                        text: 'Eliminado',
                        addclass: 'bg-' + data.status,
                        type: data.status,
                        animation: 'fade',
                        delay: 2000
                    });
                    window.vm.active--;
                }
              ).catch(function (error){
                error => {
                    console.log(error);
                    window.vm.active--;
                }
            }.bind(this));
          }
        }
      }
</script>

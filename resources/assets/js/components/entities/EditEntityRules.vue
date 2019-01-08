<template>
    <div class="">
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
									<th>Description</th>
                  <th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="points in entity.point_values">
									<td> {{points.id}} </td>
									<td>{{ points.points }}</td>
									<td>{{ points.description }}</td>
                  <td>{{ points.created_at }}</td>

                  <td><button class="btn btn-danger" @click.prevent="deleteRule(points.id)">Eliminar<i></i></button></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
      </div>

      <div class="row show-grid">
        <div class="row">
          <h6>AGREGAR UNO</h6>
            <form action="#">
              <div class="form-group">
                  <input type="hidden" name="entity_id" class="form-control" v-model="entity.id">
                  <select name="rule_id" class="form-control" v-model="rule_id">
                    <option v-for="rule in rules" v-bind:value="rule.id"> {{rule.name}}</option>
                  </select>
                  <label for="">Seleccione la regla que desea asignar</label>
                  <input type="text" name="value" class="form-control" v-model="value" v-if="rule_id == 6 || rule_id  == 7">
                  <label for="">Ingrese el valor a asignar</label>
                  <textarea type="text" name="description" class="form-control" v-model="description" v-if="rule_id  == 7"></textarea>
                  <label for="">Ingrese una descripción detallando la asignación de puntos</label>
              </div>
              <div class="form-group">
                <button  @click.prevent="createRule" class="btn btn-primary">Crear <i class="icon-paperplane ml-2"></i></button>
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
                    points_values:[],
                    invoices:[],
                    redemptions:[],
                    entity_goals:'',
                    entity_information:'',
                    description: '',
                    modifier: '',
                    weight: '100',
                    client_id: null
                },

                rule_id:'',
                value:0,
                description:'',
                clients: [],
                modifiers: [

                ],
                rules:[],
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
                      }
                  }
                ).catch();

                axios.get('/api/rules/all').then(
                  ({data}) => {
                    this.rules = data;
                  }
                ).catch(
                  ({error}) => {
                    console.log(error);
                  }
                )
            }

        },
        methods: {
            createRule(){
                axios.post('/api/entities/set-rule',{entity_id:this.entity.id, rule_id:this.rule_id, value:this.value,description:this.description}).then(
                  ({data}) => {
                    console.log(data);
                  }
                ).catch(function(error){
                  console.log(error);
                }.bind(this));
            },

            deleteRule(id){
              console.log(id);
              axios.post('/api/entities/del-rule',{id:id,entity_id:this.entity.id}).then(
                ({data}) => {
                  console.log(data);
                }
              ).catch(function(error){
                console.log(error);
              }.bind(this));
            }
        }
    }
</script>

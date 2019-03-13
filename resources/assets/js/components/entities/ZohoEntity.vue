<template>
  <div class="">
    <div class="col-md-6">
        <table  class="table table-xs" v-if="entity.type_id == 1">
          <thead>
            <tr>
              <th>Zoho Name</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Cedula                      </td><td> {{entity.identification }} </td></tr>
            <tr><td>Cedula_Ascesor              </td><td> {{entity.entity_information[0].no_identificacion_asesor }} </td></tr>
            <tr><td>Date_of_Birth               </td><td> {{entity.entity_information[0].birthdate }} </td></tr>
            <tr><td>Email                       </td><td> {{entity.entity_information[0].mail }} </td></tr>
            <tr><td>Email_Opt_Out               </td><td> TRUE</td></tr>
            <tr><td>Fecha_de_Registro           </td><td> {{entity.entity_information[0].created_at }} </td></tr>
            <tr><td>First_Name                  </td><td> {{entity.entity_information[0].nombres }} </td></tr>
            <tr><td>Last_Name                   </td><td> {{entity.entity_information[0].apellidos }} </td></tr>
            <tr><td>Mobile                      </td><td> {{entity.entity_information[0].telephone }} </td></tr>
            <tr><td>Nombre_de_Asesor            </td><td> {{entity.entity_information[0].asesor }} </td></tr>
            <tr><td>Genero                      </td><td> {{entity.entity_information[0].gender }} </td></tr>
            <tr><td>Convertir_en_Contacto       </td><td> {{entity.entity_information[0].zoho_lead_to_contact }}</td></tr>
          </tbody>
        </table>
        <table  class="table table-xs" v-if="entity.type_id == 2">
          <thead>
            <tr>
              <th>Zoho Name</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Account_Name </td><td>  null</td></tr>
            <tr><td>Cedula_de_cliente </td><td> <a v-bind:href="'/entities/'+entity.entity_id+'/edit'"> Ver </a> </td></tr>
            <tr><td>Codigo_de_restaurante </td><td>  {{entity.entity_information[0].restaurant_code}}</td></tr>
            <tr><td>Fecha_de_Transaccion </td><td>  {{entity.entity_information[0].invoice_date_up}}</td></tr>
            <tr><td>Invoice_Number </td><td>  null</td></tr>
            <tr><td>Owner </td><td>  null</td></tr>
            <tr><td>Kokoripuntos_Acumulados </td><td>  null</td></tr>
            <tr><td>Subject </td><td> {{entity.identification}} </td></tr>
            <tr><td>Tipo_de_Venta </td><td>  {{entity.entity_information[0].sale_type}}</td></tr>
            <tr><td>Valor_de_compra </td><td> null</td></tr>
          </tbody>
        </table>
        <table  class="table table-xs" v-if="entity.type_id == 3">
          <thead>
            <tr>
              <th>Zoho Name</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr><td>Created_By </td><td> 3609958000001218233,</td></tr> -->
            <!-- <tr><td>Created_Time </td><td> $date</td></tr> -->
            <!-- <tr><td>Description </td><td> </td></tr> -->
            <!-- <tr><td>Modified_By </td><td> 677524459</td></tr>
            <tr><td>Modified_Time </td><td> $date</td></tr> -->
            <tr><td>Product_Active </td><td> TRUE</td></tr>
            <tr><td>Product_Category </td><td> {{entity.entity_information[0].family_name}}</td></tr>
            <tr><td>Product_Code </td><td> {{entity.entity_information[0].product_code}}</td></tr>
            <tr><td>Record_Image </td><td> NULL</td></tr>
            <tr><td>Product_Name </td><td> {{entity.entity_information[0].name}}</td></tr>
            <!-- <tr><td>Owner </td><td> NULL</td></tr> -->
          </tbody>
        </table>
    </div>
    <div class="col-md-6">
      <form action="#">
        <div class="form-group">
            <table class="table table-xs">
              <thead>
                <tr>
                  <th>Zoho Module</th>
                  <th>Zoho Id</th>
                </tr>
              </thead>
                <tbody>
                  <tr>
                    <td>{{entity.zoho_module}}</td>
                    <td>{{entity.zoho_id}}</td>
                  </tr>
                </tbody>
            </table>
            <button v-if="entity.zoho_id == null" class="btn btn-success" type="button" name="button" @click.prevent="createZoho()">Enviar a Zoho</button>
        </div>
      </form>
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
                          console.log('Entity',this.entity)
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
            createZoho(){
              window.vm.active--;
              axios.get('/api/entities/create-zoho/' + this.entity.id + '/Leads').then(
                ({data}) => {
                    if (data.message) new PNotify({
                        text: data.message,
                        addclass: 'bg-' + data.status,
                        type: data.status,
                        animation: 'fade',
                        delay: 10000
                    });
                    ;
                    if (data.status == 'success') {
                      this.entity = data.entity
                      setTimeout(function() {window.vm.active++},1000)
                    }else{
                      setTimeout(function() {window.vm.active++},1000)
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
                  setTimeout(function() {window.vm.active++},1000)
              }.bind(this));
            }
        }
    }
</script>

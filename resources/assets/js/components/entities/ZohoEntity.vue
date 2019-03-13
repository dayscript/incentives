<template>
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

        <button class="btn btn-danger" type="button" name="button" @click.prevent="createZoho()"></button>
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

<template>

  <form ref="form" class="" action="/tools/export" method="POST">
    <div class="form-group">
      <label for="">Modelo</label>
      <select type="text" name="model" class="form-control" v-model="model" @change="submit">
        <option v-for="(item,index) in models" v-bind:value="index"> {{item}} </option>
      </select>
    </div>

    <div class="form-group" v-if="attributes">
      <select class="" name="attribute" v-model="attribute">
        <option v-for="(item,index) in attributes" v-bind:value="index" >{{item}}</option>
      </select>
    </div>

    <div class="form-group" v-if="model">
      <select class="" name="operator" v-model="operator">
        <option value=">">Mayor</option>
        <option value="<">Menor</option>
        <option value="=">Igual</option>
        <option value="<=">Menor Igual</option>
        <option value=">=">Mayor Igual</option>
      </select>
    </div>

    <div class="form-group">
      <label for="">Valor</label>
      <input type="text" name="value" value="">
    </div>

    <div class="form-group" v-if="response.message">
      <label for="">{{response.message}}</label>
    </div>
    <input type="submit"></input>
  </form>

</template>

<script>
    export default {
        props: {
          models:{},
          attributes:{},
          response:{},
          model:'',
          attribute:'',
        },
        data(){
          return {
            operator:'',
            errors: {},
            adittionaldata: {
                '_token': window.Laravel.csrfToken,
                'ajax': true,

            },
          }
        },
        mounted() {

        },
        methods: {
          submit(){
            this.$refs.form.submit();
          }
        }
    }
</script>

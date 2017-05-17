<template>
    <form action="#">
        <div class="form-group" :class="{'has-error': errors.name}">
            <label>Nombre:</label>
            <input type="text" class="form-control" placeholder="Nombre de la regla" v-model="rule.name" v-on:keyup="resetErrors('name')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                <span ref="noerrors.name" v-else class="help-block">Escribe el nombre de la regla de acumulación</span>
            </transition>
        </div>
        <div class="form-group" :class="{'has-error': errors.description}">
            <label>Descripción:</label>
            <input type="text" class="form-control" placeholder="Descripción de la regla" v-model="rule.description" v-on:keyup="resetErrors('description')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.description" v-if="errors.description" class="help-block text-danger">{{ errors.description[0] }}</span>
                <span ref="noerrors.description" v-else class="help-block">Escribe una descripción sobre el contenido de esta regla</span>
            </transition>
        </div>
        <div class="form-group" :class="{'has-error': errors.points}">
            <label>Puntos ganados:</label>
            <input type="number" class="form-control" placeholder="Puntos a asignar" v-model="rule.points" v-on:keyup="resetErrors('points')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.points" v-if="errors.points" class="help-block text-danger">{{ errors.points[0] }}</span>
                <span ref="noerrors.points" v-else class="help-block">Cantidad de puntos que se obtienen con esta regla</span>
            </transition>
        </div>
        <div class="text-right">
            <a class="btn" href="/rules"><i class=" icon-arrow-left15 left"></i> Regresar</a>
            <button v-if="rule.id>0" @click.prevent="updateRule" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
            </button>
            <button v-else @click.prevent="createRule" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
            <button v-if="rule.id>0" @click.prevent="deleteRule" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            rule_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                rule: {
                    id: this.rule_id,
                    name: '',
                    description: '',
                    points: null
                },
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                }
            }
        },
        mounted() {
            if (this.rule_id > 0) {
                axios.get('/rules/' + this.rule_id).then(
                    ({data}) => {
                        if (data.rule) {
                            this.rule = data.rule;
                        }
                    }
                ).catch();
            }
        },
        methods: {
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createRule(){
                window.vm.active++;
                axios.post('/rules', this.rule).then(
                    ({data}) => {
                        if (data.rule) this.rule = data.rule;
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
                axios.put('/rules/' + this.rule.id, this.rule).then(
                    ({data}) => {
                        if (data.rule) this.rule = data.rule;
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
                    axios.delete('/rules/' + this.rule.id).then(
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
                                document.location.href = '/clients';
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

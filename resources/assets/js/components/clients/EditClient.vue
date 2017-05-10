<template>
    <form action="#">
        <div class="form-group" :class="{'has-error': errors.name}">
            <label>Nombre:</label>
            <input type="text" class="form-control" placeholder="Nombre del cliente" v-model="client.name" v-on:keyup="resetErrors('name')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                <span ref="noerrors.name" v-else class="help-block">Escribe el nombre del cliente</span>
            </transition>
        </div>
        <div class="form-group">
            <label>Imagen:
                <small>Recomendamos una imagen cuadrada con el logo o ícono de la empresa cliente.</small>
            </label>
            <el-upload
                    class="avatar-uploader"
                    :data="adittionaldata"
                    action="/uploads"
                    :show-file-list="false"
                    :on-success="handleAvatarSuccess"
                    :before-upload="beforeAvatarUpload"
                    name="file">
                <img v-if="client.image" :src="client.image" class="avatar-image">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <span class="help-block">Haz click o arrastra un archivo para cambiar la imagen. <br>
                Formatos aceptados: gif, png, jpg. Tamaño máximo del archivo: 2MB</span>
        </div>
        <div class="text-right">
            <a class="btn" href="/clients"><i class=" icon-arrow-left15 left"></i> Regresar</a>
            <button v-if="client.id>0" @click.prevent="updateClient" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i>
            </button>
            <button v-else @click.prevent="createClient" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
            <button v-if="client.id>0" @click.prevent="deleteClient" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            client_id: {
                type: Number,
                default: 0
            },
        },
        data(){
            return {
                client: {
                    id: this.client_id,
                    name: '',
                    image: null
                },
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                    'folder': 'clients/' + this.client_id
                }
            }
        },
        mounted() {
            if (this.client_id > 0) {
                axios.get('/clients/' + this.client_id).then(
                    ({data}) => {
                        if (data.client) {
                            this.client = data.client;
                        }
                    }
                ).catch();
            }
        },
        methods: {
            handleAvatarSuccess(res, file) {
                this.client.image = res.path;
            },
            beforeAvatarUpload(file) {
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                    this.$message.error('La imagen no puede pesar mas de 2MB!');
                }
                return isLt2M;
            },
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createClient(){
                window.vm.active++;
                axios.post('/clients', this.client).then(
                    ({data}) => {
                        if (data.client) {
                            this.client = data.client;
                            this.adittionaldata.folder = 'clients/' + this.client.id;
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
            updateClient(){
                window.vm.active++;
                axios.put('/clients/' + this.client.id, this.client).then(
                    ({data}) => {
                        if (data.client) this.client = data.client;
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
            deleteClient(){
                if (confirm('¿Estás seguro que quieres eliminar este registro?')) {
                    window.vm.active++;
                    axios.delete('/clients/' + this.client.id).then(
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

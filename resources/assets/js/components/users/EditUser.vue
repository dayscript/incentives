<template>
    <form action="#">
        <div class="form-group" :class="{'has-error': errors.name}">
            <label>Nombre:</label>
            <input type="text" class="form-control" placeholder="Tu nombre completo" v-model="user.name" v-on:keyup="resetErrors('name')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.name" v-if="errors.name" class="help-block text-danger">{{ errors.name[0] }}</span>
                <span ref="noerrors.name" v-else class="help-block">Escribe tu nombre completo</span>
            </transition>
        </div>
        <div class="form-group" :class="{'has-error': errors.position}">
            <label>Cargo:</label>
            <input type="text" class="form-control" placeholder="Tu cargo en la empresa" v-model="user.position" v-on:keyup="resetErrors('position')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.position" v-if="errors.position" class="help-block text-danger">{{ errors.position[0] }}</span>
                <span ref="noerrors.position" v-else class="help-block">Nombre de tu cargo en la empresa</span>
            </transition>
        </div>
        <div class="form-group" :class="{'has-error': errors.email}">
            <label>Correo electrónico:</label>
            <input type="text" class="form-control" placeholder="Tu dirección de e-mail" v-model="user.email" v-on:keyup="resetErrors('email')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.email" v-if="errors.email" class="help-block text-danger">{{ errors.email[0] }}</span>
                <span ref="noerrors.email" v-else class="help-block">Dirección de correo electrónico principal</span>
            </transition>
        </div>

        <div class="form-group" :class="{'has-error': errors.password}">
            <label>Contraseña:</label>
            <input type="password" class="form-control" placeholder="Escribe una contraseña" v-model="user.password"
                   v-on:keyup="resetErrors('password')">
            <transition enter-active-class="animated fadeIn" mode="out-in" leave-active-class="animated fadeOut">
                <span ref="errors.password" v-if="errors.password" class="help-block text-danger">{{ errors.password[0] }}</span>
                <span ref="noerrors.password" v-else class="help-block">Escriba una contraseña segura</span>
            </transition>
        </div>

        <div class="form-group">
            <label>Ciudad:</label>
            <select name="city_id" id="city_id" class="select" v-model="user.city_id">
                <optgroup :label="country" v-for="(items, country) in cities">
                    <option v-for="city in items" :value="city.id">{{ city.name }}</option>
                </optgroup>
            </select>
        </div>

        <div class="form-group">
            <label>Imagen de perfil:</label>
            <el-upload
                    class="avatar-uploader"
                    :data="adittionaldata"
                    action="/uploads"
                    :show-file-list="false"
                    :on-success="handleAvatarSuccess"
                    :before-upload="beforeAvatarUpload"
                    name="file">
                <img v-if="user.avatar" :src="user.avatar" class="avatar-image">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <!--<input type="file" class="file-styled">-->
            <span class="help-block">Haz click o arrastra un archivo para cambiar tu imagen. <br>
                Formatos aceptados: gif, png, jpg. Tamaño máximo del archivo: 2MB</span>
        </div>
        <div class="text-right">
            <a class="btn" href="/users"><i class=" icon-arrow-left15 left"></i> Regresar</a>
            <button v-if="user.id>0" @click.prevent="updateUser" class="btn btn-success">Guardar <i class="icon-checkmark4 position-right"></i></button>
            <button v-else @click.prevent="createUser" class="btn btn-success">Crear <i class="icon-checkmark4 position-right"></i></button>
            <button v-if="user.id>0" @click.prevent="deleteUser" class="btn btn-danger">Eliminar <i class="icon-trash position-right"></i>
            </button>

        </div>
    </form>
</template>

<script>
    export default {
        props: {
            user_id: {
                type: Number,
                default: 0
            },
            cities:{
                type: Object,
                default:{}
            }
        },
        data(){
            return {
                user: {
                    id: this.user_id,
                    name: '',
                    email: '',
                    position: '',
                    password: '',
                    city_id: null,
                    avatar: null
                },
                errors: {},
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                    'folder': 'avatars/' + this.user_id
                }
            }
        },
        mounted() {
            if (this.user_id>0) {
                axios.get('/users/' + this.user_id).then(
                    ({data}) => {
//                        if (data.cities) this.cities = data.cities;
                        if (data.user) {
                            this.user = data.user;
                        }
                    }
                ).catch();
            }
            setTimeout(function () {
                $('.select').select2();
            }, 300);

        },
        methods: {
            handleAvatarSuccess(res, file) {
                this.user.avatar = res.path;
            },
            beforeAvatarUpload(file) {
//                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

//                if (!isJPG) {
//                    this.$message.error('Avatar picture must be JPG format!');
//                }
                if (!isLt2M) {
                    this.$message.error('La imagen no puede pesar mas de 2MB!');
                }
                return isLt2M;
            },
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            createUser(){
                window.vm.active++;
                axios.post('/users', this.user).then(
                    ({data}) => {
                        if (data.user) {
                            this.user = data.user;
                            this.adittionaldata.folder = 'avatars/' + this.user.id;
                        }
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
            updateUser(){
                window.vm.active++;
                this.user.city_id = $('#city_id').val();
                axios.put('/users/' + this.user.id, this.user).then(
                    ({data}) => {
                        if (data.user) this.user = data.user;
//                        $('#city_id').val(data.user.city_id);
//                        $('.select').select2();
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
            deleteUser(){
                if (confirm('¿Estás seguro que quieres eliminar este registro?')) {
                    window.vm.active++;
                    axios.delete('/users/' + this.user.id).then(
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
                                document.location.href = '/users';
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

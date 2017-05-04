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
            <button @click.prevent="updateProfile" class="btn btn-primary">Guardar <i class="icon-user-check position-right"></i></button>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['user_id'],
        data(){
            return {
                user: {
                    name: '',
                    email: '',
                    position: '',
                    password: '',
                    city_id: null
                },
                errors: {},
                cities: [],
                adittionaldata: {
                    '_token': window.Laravel.csrfToken,
                    'ajax': true,
                    'folder': 'avatars'
                }
            }
        },
        mounted() {
            if (this.user_id) {
                axios.get('/users/' + this.user_id).then(
                    ({data}) => {
                        if (data.cities) this.cities = data.cities;
                        if (data.user) this.user = data.user;
                        setTimeout(function () {
                            $('.select').select2();
                        }, 300);
                    }
                ).catch();
            }
        },
        methods: {
            handleAvatarSuccess(res, file) {
                console.log(res.path);
//                this.user.avatar = URL.createObjectURL(file.raw);
                this.user.avatar = res.path;
            },
            beforeAvatarUpload(file) {
//                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

//                if (!isJPG) {
//                    this.$message.error('Avatar picture must be JPG format!');
//                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }
                return isLt2M;
            },
            resetErrors(field){
                Vue.delete(this.errors, field);
            },
            updateProfile(){
                window.vm.active++;
                this.user.city_id = $('#city_id').val();
                axios.put('/users/' + this.user_id, this.user).then(
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

            }
        }
    }
</script>

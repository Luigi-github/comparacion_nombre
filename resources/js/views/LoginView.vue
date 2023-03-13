<template>
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                    Buscador de personas
                </h2>
            </div>
            <form @submit.prevent="handleSubmit" autocomplete="off" novalidate class="mt-8 space-y-6">
                <div class="border bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"
                     v-if="(v$.$dirty && v$.$invalid) || withError">
                    <div v-for="error of v$.$errors" :key="error.$uid">
                        {{ error.$property }} - {{ error.$message }}
                    </div>
                    <div v-if="withError">
                        {{ message }}
                    </div>
                </div>
                <div class="-space-y-px rounded-md shadow-sm">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" type="email" v-model="email"
                               class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Email">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Contraseña</label>
                        <input id="password" type="password" v-model="password"
                               class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Contraseña">
                    </div>
                </div>
                <div>
                    <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Iniciar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import { apiCall } from '../api';
    import { useVuelidate } from '@vuelidate/core';
    import { email, required, maxLength } from '@vuelidate/validators';

    export default {
        setup () {
            return { v$: useVuelidate() }
        },
        data () {
            return {
                email: null,
                password: null,
                withError: false,
                message: null
            }
        },
        validations () {
            return {
                email: {
                    email,
                    required,
                    maxLengthValue: maxLength(255),
                },
                password: {
                    required,
                    betweenValue: maxLength(255)
                }
            }
        },
        methods: {
            async handleSubmit () {
                const isFormCorrect = await this.v$.$validate()
                if (!isFormCorrect) return;

                let data = this.$data;
                let router = this.$router;
                apiCall('POST', '/users/token', {
                    email: data.email,
                    password: data.password
                }).then(function (response){
                    if(response.status === 200){
                        localStorage.setItem('token', response.data.access_token);
                        router.push('searcher');
                    }
                }).catch(function (error){
                    if(error.response.status === 401) {
                        data.withError = true;
                        data.message = error.response.data.message;
                    }
                });
            }
        }
    }
</script>

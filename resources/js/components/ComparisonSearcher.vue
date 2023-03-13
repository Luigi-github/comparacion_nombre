<template>
    <form @submit.prevent="handleSubmit" autocomplete="off" novalidate class="flex flex-row mb-5">
        <div class="bg-white">
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nombres y apellidos</label>
            <input type="text" id="name" maxlength="255" v-model="name"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
        <div class="pl-5 bg-white">
            <label for="coincidence" class="block text-sm font-medium leading-6 text-gray-900">Porcentaje coincidencia</label>
            <input type="number" id="coincidence" step="0.01" min="0" max="100" v-model="coincidence"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
        <div class="pl-5 mt-8 bg-white">
            <div class="text-right">
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Buscar</button>
            </div>
        </div>
        <div class="ml-10 border px-4 py-3 rounded relative" role="alert"
            :class="{
                'bg-red-100 border-red-400 text-red-700': (v$.$dirty && v$.$invalid) || messageType === 3,
                'bg-green-100 border-green-400 text-green-700': messageType === 1,
                'bg-orange-100 border-orange-400 text-orange-700': messageType === 2,
            }"
            v-if="(v$.$dirty && v$.$invalid) || !!messageType">
            <div v-for="error of v$.$errors" :key="error.$uid">
                {{ error.$property }} - {{ error.$message }}
            </div>
            <div v-if="!!searchRegister">
                <span v-if="!!searchRegister.id">UUID: {{ searchRegister.id }} - </span>
                {{ searchRegister.execution_state }}
            </div>
        </div>
    </form>
</template>

<script>
    import { apiCall } from '../api';
    import { useVuelidate } from '@vuelidate/core';
    import { required, maxLength, between } from '@vuelidate/validators';

    export default {
        setup () {
            return { v$: useVuelidate() }
        },
        data () {
            return {
                name: null,
                coincidence: null,
                messageType: null,
                searchRegister: null
            }
        },
        validations () {
            return {
                name: {
                    required,
                    maxLengthValue: maxLength(255),
                },
                coincidence: {
                    required,
                    betweenValue: between(0, 100)
                }
            }
        },
        methods: {
            async handleSubmit () {
                const isFormCorrect = await this.v$.$validate()
                if (!isFormCorrect) return;

                let comp = this;
                let data = comp.$data;
                data.messageType = null;
                apiCall('GET', '/match-names?name='+data.name+'&match_percentage='+data.coincidence)
                .then(function (response){
                    data.searchRegister = response.data;
                    data.messageType = response.status === 200 ? 1 : 2;
                    comp.$emit('result', response.data);
                }).catch(function (error){
                    data.searchRegister = error.response.data;
                    if(error.response.status === 400 || error.response.status === 409) {
                        data.messageType = 3;
                    }
                    comp.$emit('result', error.response.data);
                });
            }
        }
    }
</script>

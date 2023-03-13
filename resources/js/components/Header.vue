<template>
    <nav class="bg-gray-800">
        <div class="mx-auto">
            <div class="relative flex h-16 items-center justify-between">
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <router-link to="/searcher"
                                         class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                                         active-class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                                         exact>
                                Buscador
                            </router-link>
                            <router-link to="/unique-results"
                                         class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                                         active-class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                                         exact>
                                Consulta resultados
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 mr-5">
                    <button type="button" @click="logout()"
                            class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        Salir
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$router.push('login');
        }
    }
}
</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="table-auto" width="100%">
            <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left">Tipo persona</th>
                    <th scope="col" class="px-6 py-3 text-left">Tipo cargo</th>
                    <th scope="col" class="px-6 py-3 text-right">Años activo</th>
                    <th scope="col" class="px-6 py-3 text-left">Departamento</th>
                    <th scope="col" class="px-6 py-3 text-left">Municipio</th>
                    <th scope="col" class="px-6 py-3 text-left">Localidad</th>
                    <th scope="col" class="px-6 py-3 text-right">% Coincidencia</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="coincidence of result.coincidences" :key="coincidence.$uid">
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.name }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.person_type }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.position_type }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-right">
                        {{ coincidence.active_years }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.department }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.town }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-left">
                        {{ coincidence.location }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-right">
                        {{ coincidence.match_percentage.toFixed(2) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['result']
    }
</script>

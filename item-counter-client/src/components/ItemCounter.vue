<template>
    <div>
        <div v-if="isLoading">Loading item counters...</div>
        <div v-else>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Title</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="item in items">
                    <tr v-bind:key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.count }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
        <item-counter-form @completed="addItemCounter"></item-counter-form>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import ItemCounterForm from './ItemCounterForm.vue'
import { API_BASE_URL } from '../config'

export default {
    components: {
        ItemCounterForm
    },
    data() {
        return {
            items: {},
            isLoading: true,
            countUpdatingTable: []
        }
    },
    async created () {
        axios.defaults.headers.common['Authorization'] = `Bearer ${await this.$auth.getAccessToken()}`
        try {
            const response = await axios.get(API_BASE_URL + '/items')
            this.items = response.data
            this.isLoading = false
        } catch (e) {
            // handle the authentication error here
        }
    },
    methods: {
        addItemCounter(item) {
            this.items.push(item)
        }
    }
}
</script>
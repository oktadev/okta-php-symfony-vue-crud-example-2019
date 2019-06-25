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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="item in items">
                    <tr v-bind:key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.count }}</td>
                        <td>
                            <button class="button is-primary" v-bind:class="{ 'is-loading' : isCountUpdating(item.id) }" @click="increaseCount(item)">Increase</button>&nbsp;
                            <button class="button is-primary" v-bind:class="{ 'is-loading' : isDeleting(item.id) }" @click="deleteItemCounter(item.id)">Delete</button>
                        </td>
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
import Vue from 'vue'
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
        },
        isCountUpdating(id) {
            return this.countUpdatingTable[id]
        },
        async increaseCount(item) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${await this.$auth.getAccessToken()}`
            Vue.set(this.countUpdatingTable, item.id, true)
            axios.post(API_BASE_URL + '/items/' + item.id + '/count')
                .then(response => {
                    item.count = response.data.count
                    this.countUpdatingTable[item.id] = false
                })
                .catch(() => {
                    // handle authentication and validation errors here
                    this.countUpdatingTable[item.id] = false
                })
        },
        isDeleting(id) {
            let index = this.items.findIndex(item => item.id === id)
            return this.items[index].isDeleting
        },
        async deleteItemCounter(id) {
            let index = this.items.findIndex(item => item.id === id)
            Vue.set(this.items[index], 'isDeleting', true)
            await axios.delete(API_BASE_URL + '/items/' + id)
            this.items.splice(index, 1)
        }
    }
}
</script>
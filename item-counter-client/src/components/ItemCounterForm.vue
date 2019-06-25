<template>
    <form @submit.prevent="onSubmit">
        <span class="help is-danger" v-text="errors"></span>

        <div class="field">
            <div class="control">
                <input class="input" type="title" placeholder="enter title..." v-model="title" @keydown="errors = ''">
            </div>
        </div>

        <button class="button is-primary" v-bind:class="{ 'is-loading' : isLoading }">Add Item Counter</button>
    </form>
</template>

<script>
import axios from 'axios'
import { API_BASE_URL } from '../config'

export default {
    data() {
        return {
            title: '',
            errors: '',
            isLoading: false
        }
    },
    methods: {
        onSubmit() {
            this.isLoading = true
            this.postItemCounter()
        },
        async postItemCounter() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${await this.$auth.getAccessToken()}`
            axios.post(API_BASE_URL + '/items', this.$data)
                .then(response => {
                    this.title = ''
                    this.isLoading = false
                    this.$emit('completed', response.data)
                })
                .catch(error => {
                    // handle authentication and validation errors here
                    this.errors = error.response.data.errors
                    this.isLoading = false
                })
        }
    }
}
</script>
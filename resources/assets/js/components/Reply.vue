<template>
  <div class="card mb-4" :id="'reply-'+id">
      <div class="card-header">
        <div class="level">
          <h6 class="flex">
              <a :href="'/profiles/'+data.owner.name" 
              v-text="data.owner.name">
              </a> ответил <span v-text="ago"></span>
          </h6>

          <div v-if="signnedIn">
            <favorite :reply="data"></favorite>
          </div>
        </div>
      </div>
  
      <div class="card-body">
            <div v-if="editing">
              <div class="form-group">
                  <textarea class="form-control" v-model="body"></textarea>
              </div>

              <button class="btn btn-sm btn-success" @click="update">Обновить</button>
              <button class="btn btn-sm btn-link" @click="editing = false">Отменить</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        
        <div class="card-footer level" v-if="canUpdate">
          <button type="button" @click="editing = true" class="btn btn-dark btn-sm mr-2">Редактировать</button>
          <button type="button" @click="destroy" class="btn btn-danger btn-sm mr-2">Удалить</button>
        </div>
      </div>
</template>


<script>

import Favorite from './Favorite.vue';
import moment from 'moment';

export default {

  props: ['data'],
  
  components: { Favorite },

  data() {
    return {
      editing: false,
      id: this.data.id,
      body: this.data.body
    }
  },

  computed: {
    ago() {
      return moment(this.data.created_at).fromNow();
    },

    signnedIn() {
      return window.App.signIn;
    },

    canUpdate() {
      return this.authorize(user => this.data.user_id == window.App.user.id);
    }
  },

  methods: {
    update() {
      axios.patch('/replies/' + this.data.id, {
        body: this.body
      });

      this.editing = false;

      flash('Обновлено!');
    },

    destroy() {
      axios.delete('/replies/' + this.data.id);

      this.$emit('deleted', this.data.id);
    }
  }
}
</script>

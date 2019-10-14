<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="card mb-4" id="reply-{{ $reply->id }}">
        <div class="card-header">
          <div class="level">
            <h6 class="flex">
                <a href="{{ route('profile', $reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a> ответил {{ $reply->created_at->diffForHumans() }}...
            </h6>
            @auth
            <div>
              <favorite :reply="{{ $reply }}"></favorite>
            </div>
            @endauth
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
          @can('update', $reply)
          <div class="card-footer level">
            <button type="button" @click="editing = true" class="btn btn-dark btn-sm mr-2">Edit</button>
            <button type="button" @click="destroy" class="btn btn-danger btn-sm mr-2">Delete</button>
          </div>
          @endcan
        </div>
</reply>
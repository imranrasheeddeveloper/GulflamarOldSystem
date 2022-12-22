<template>
  <!-- Need to add height inherit because Vue 2 don't support multiple root ele -->
  <div style="height: inherit">
    <div
      class="body-content-overlay"
      :class="{'show': mqShallShowLeftSidebar}"
      @click="mqShallShowLeftSidebar = false"
    />
    <div class="todo-app-list">

      <!-- App Searchbar Header -->
      <div class="app-fixed-search d-flex align-items-center">

        <!-- Toggler -->
        <div class="sidebar-toggle d-block d-lg-none ml-1">
          <feather-icon
            icon="MenuIcon"
            size="21"
            class="cursor-pointer"
            @click="mqShallShowLeftSidebar = true"
          />
        </div>

        <!-- Searchbar -->
        <div class="d-flex align-content-center justify-content-between w-100">
          <b-input-group class="input-group-merge">
            <b-input-group-prepend is-text>
              <feather-icon
                icon="SearchIcon"
                class="text-muted"
              />
            </b-input-group-prepend>
            <b-form-input
              :value="searchQuery"
              placeholder="Search task"
              @input="updateRouteQuery"
              @click="close"
            />
          </b-input-group>
        </div>

        <!-- Dropdown -->
        <div class="dropdown">
          <b-dropdown
            variant="link"
            no-caret
            toggle-class="p-0 mr-1"
            right
          >
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
              />
            </template>
            <b-dropdown-item @click="resetSortAndNavigate">
              Reset Sort
            </b-dropdown-item>
            <b-dropdown-item :to="{ name: $route.name, query: { ...$route.query, sort: 'title-asc' } }">
              Sort A-Z
            </b-dropdown-item>
            <b-dropdown-item :to="{ name: $route.name, query: { ...$route.query, sort: 'title-desc' } }">
              Sort Z-A
            </b-dropdown-item>
            <b-dropdown-item :to="{ name: $route.name, query: { ...$route.query, sort: 'assignee' } }">
              Sort Assignee
            </b-dropdown-item>
            <b-dropdown-item :to="{ name: $route.name, query: { ...$route.query, sort: 'due-date' } }">
              Sort Due Date
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </div>

      <!-- Todo List -->
      <vue-perfect-scrollbar
        :settings="perfectScrollbarSettings"
        class="todo-task-list-wrapper list-group scroll-area"
      >
        <draggable
          v-model="tasks"
          handle=".draggable-task-handle"
          tag="ul"
          class="todo-task-list media-list"
        >
          <li
            v-for="task in tasks"
            :key="task.id"
            class="todo-item"
            :class="{ 'completed': task.isCompleted==1 }"
            @click="handleTaskClick(task)"
          >
            <feather-icon
              icon="MoreVerticalIcon"
              class="draggable-task-handle d-inline"
            />
            <div class="todo-title-wrapper">
              <div class="todo-title-area">
                <div class="title-wrapper">
                  <b-form-checkbox
                    :checked="task.isCompleted==1"
                    @click.native.stop
                    @change="updateThisTaskCompleted(task)"
                  />
                  <p class="todo-title"><b>{{ task.title }}</b></p>
                  <span class="todo-description" v-html="task.description">{{ task.description }}</span>
                  

                </div>
              </div>
              <div class="todo-item-action">
                <div class="badge-wrapper mr-1">
                  <b-badge
                    pill
                    :variant="`light-${resolveTagVariant(task.tag)}`"
                    class="text-capitalize"
                  >
                    {{ task.tag }}


                  </b-badge>
                </div>
                <small class="text-nowrap text-muted mr-1">{{ formatDate(task.dueDate, { month: 'short', day: 'numeric'}) }}</small>
                <b-avatar
                  v-if="task.assignee"
                  size="32"
                  :src="task.assignee.avatar"
                  :variant="`light-${resolveAvatarVariant(task.tag)}`"
                  :text="avatarText(task.assignee.name)"
                />
                <b-avatar
                  v-else
                  size="32"
                  variant="light-secondary"
                >
                  <feather-icon
                    icon="UserIcon"
                    size="16"
                  />
                </b-avatar>

              </div>
            </div>

          </li>
        </draggable>
        <div
          class="no-results"
          :class="{'show': !tasks.length}"
        >
          <h5>No Items Found</h5>
        </div>
      </vue-perfect-scrollbar>
      
    </div>

    <!-- Task Handler -->
    <todo-task-handler-sidebar
      v-model="isTaskHandlerSidebarActive"
      :task="task"
      :clear-task-data="clearTaskData"
      @remove-task="removeTask"
      @add-task="addTask"
      @update-task="updateTask"
      @fetch-task="fetchTasks"

    />

    <!-- Sidebar -->
    <portal to="content-renderer-sidebar-left">
      <todo-left-sidebar
        :task-tags="taskTags"
        :is-task-handler-sidebar-active.sync="isTaskHandlerSidebarActive"
        :class="{'show': mqShallShowLeftSidebar}"
        @close-left-sidebar="mqShallShowLeftSidebar = false"
      />
    </portal>
    
  </div>
</template>

<script>
import store from '@/store'
import {
  ref, watch, computed, onUnmounted,
} from '@vue/composition-api'
import {
  BFormInput, BInputGroup, BInputGroupPrepend, BDropdown, BDropdownItem,
  BFormCheckbox, BBadge, BAvatar,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import draggable from 'vuedraggable'
import { formatDate, avatarText } from '@core/utils/filter'
import { useRouter } from '@core/utils/utils'
import { useResponsiveAppLeftSidebarVisibility } from '@core/comp-functions/ui/app'
import TodoLeftSidebar from './TodoLeftSidebar.vue'
import todoStoreModule from './todoStoreModule'
import TodoTaskHandlerSidebar from './TodoTaskHandlerSidebar.vue'
import axios from '@axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
  components: {
    BFormInput,
    BInputGroup,
    BInputGroupPrepend,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
    BBadge,
    BAvatar,
    draggable,
    VuePerfectScrollbar,

    // App SFC
    TodoLeftSidebar,
    TodoTaskHandlerSidebar,
  },
  setup() {
    const TODO_APP_STORE_MODULE_NAME = 'app-todo'

    // Register module
    if (!store.hasModule(TODO_APP_STORE_MODULE_NAME)) store.registerModule(TODO_APP_STORE_MODULE_NAME, todoStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(TODO_APP_STORE_MODULE_NAME)) store.unregisterModule(TODO_APP_STORE_MODULE_NAME)
    })

    const { route, router } = useRouter()
    const routeSortBy = computed(() => route.value.query.sort)
    const routeQuery = computed(() => route.value.query.q)
    const routeParams = computed(() => route.value.params)
    watch(routeParams, () => {
      // eslint-disable-next-line no-use-before-define
      fetchTasks()
    })

    const tasks = ref([])

    const systemOwner = ref([]);
    const sortOptions = [
      'latest',
      'title-asc',
      'title-desc',
      'assignee',
      'due-date',
    ]
    const sortBy = ref(routeSortBy.value)
    watch(routeSortBy, val => {
      if (sortOptions.includes(val)) sortBy.value = val
      else sortBy.value = val
    })
    const resetSortAndNavigate = () => {
      const currentRouteQuery = JSON.parse(JSON.stringify(route.value.query))

      delete currentRouteQuery.sort

      router.replace({ name: route.name, query: currentRouteQuery }).catch(() => {})
    }

    const blankTask = {
      id: null,
      title: '',
      dueDate: new Date(),
      description: '',
      assignee: null,
      ownerId: null,
      owner: null,
      assignTo: null,

      isCompleted: 0,
      isDeleted: false,
      isImportant: false,
    }
    const task = ref(JSON.parse(JSON.stringify(blankTask)))
    const clearTaskData = () => {
      task.value = JSON.parse(JSON.stringify(blankTask))
    }

    const addTask = val => {
            console.log('Task added todo', val);
      // return;
      store.dispatch('app-todo/addTask', val)
        .then(() => {
          // eslint-disable-next-line no-use-before-define
          fetchTasks()
        })
    }
    const removeTask = () => {
      store.dispatch('app-todo/removeTask', { id: task.value.id })
        .then(() => {
          // eslint-disable-next-line no-use-before-define
          fetchTasks()
        })
    }
    const updateTask = taskData => {
      console.log('Task updated todo', taskData);
      return;
      
      store.dispatch('app-todo/updateTask', { task: taskData })
        .then(() => {
          // eslint-disable-next-line no-use-before-define
          fetchTasks()
        })
    }

    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }

    const isTaskHandlerSidebarActive = ref(false)

    const taskTags = [
      // { title: 'Team', color: 'primary', route: { name: 'apps-todo-tag', params: { tag: 'team' } } },
      { title: 'Low', color: 'success', route: { name: 'apps-todo-tag', params: { tag: 'low' } } },
      { title: 'Medium', color: 'warning', route: { name: 'apps-todo-tag', params: { tag: 'medium' } } },
      { title: 'High', color: 'danger', route: { name: 'apps-todo-tag', params: { tag: 'high' } } },
      // { title: 'Update', color: 'info', route: { name: 'apps-todo-tag', params: { tag: 'update' } } },
    ]

    const resolveTagVariant = tag => {
      if (tag === 'team') return 'primary'
      if (tag === 'low') return 'success'
      if (tag === 'medium') return 'warning'
      if (tag === 'high') return 'danger'
      if (tag === 'update') return 'info'
      return 'primary'
    }

    const resolveAvatarVariant = tag => {
      // if (tags.includes('high')) return 'primary'
      // if (tags.includes('medium')) return 'warning'
      // if (tags.includes('low')) return 'success'
      // if (tags.includes('update')) return 'danger'
      // if (tags.includes('team')) return 'info'
      if (tag === 'team') return 'primary'
      if (tag === 'low') return 'success'
      if (tag === 'medium') return 'warning'
      if (tag === 'high') return 'danger'
      if (tag === 'update') return 'info'
      return 'primary'
    }

    // Search Query
    const searchQuery = ref(routeQuery.value)
    watch(routeQuery, val => {
      searchQuery.value = val
    })
    // eslint-disable-next-line no-use-before-define
    watch([searchQuery, sortBy], () => fetchTasks())
    const updateRouteQuery = val => {
      const currentRouteQuery = JSON.parse(JSON.stringify(route.value.query))

      if (val) currentRouteQuery.q = val
      else delete currentRouteQuery.q

      router.replace({ name: route.name, query: currentRouteQuery })
    }

    const fetchTasks = () => {
        //     store.dispatch('app-todo/fetchTasks', {
        // q: searchQuery.value,
        // filter: router.currentRoute.params.filter,
        // tag: router.currentRoute.params.tag,
        // sortBy: sortBy.value,
        //     }),
            console.log(' searchQuery.value', searchQuery.value);
            console.log('router.currentRoute.params.filter ', router.currentRoute.params.filter);
            console.log(' router.currentRoute.params.tag',router.currentRoute.params.tag);
            console.log(' sortBy.value',sortBy.value);


            
      axios.get('/getAllTasks', {
        
        params: {
                filter: router.currentRoute.params.filter,
                tag: router.currentRoute.params.tag,

        },
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            tasks.value = response.data.data;
            systemOwner.value = response.data.systemOwner;
            console.log('All task Fetched',tasks.value)
            console.log('response.data.systemOwner',response.data.systemOwner)

          }
        }
      }).catch(error => {
        console.error(error)
      })
    }

    fetchTasks()


    const handleTaskClick = taskData => {
      task.value = taskData 
      task.value['systemOwner'] = systemOwner.value ;     
      isTaskHandlerSidebarActive.value = true
    }

    // Single Task isCompleted update
    const updateTaskIsCompleted = taskData => {
      // eslint-disable-next-line no-param-reassign
      taskData.isCompleted = !taskData.isCompleted
      updateTask(taskData)
    }

    const { mqShallShowLeftSidebar } = useResponsiveAppLeftSidebarVisibility()

    return {
      // aqeel: "aqeel786",

      isSubmitting: false,
      task,
      tasks,
      removeTask,
      addTask,
      updateTask,
      clearTaskData,
      taskTags,
      searchQuery,
      fetchTasks,
      perfectScrollbarSettings,
      updateRouteQuery,
      resetSortAndNavigate,

      // UI
      resolveTagVariant,
      resolveAvatarVariant,
      isTaskHandlerSidebarActive,

      // Click Handler
      handleTaskClick,

      // Filters
      formatDate,
      avatarText,

      // Single Task isCompleted update
      updateTaskIsCompleted,

      // Left Sidebar Responsive
      mqShallShowLeftSidebar,
      allTasks:[],
      

    }
  },
  mounted() {
    // this.getAllTasks()
  },
  methods: {
    close(){
      this.fetchTasks()
      console.log('close');

    },
        updateThisTaskCompleted(taskData){
      // this.fetchTasks()
      console.log('updated task method',taskData);
      console.log('isCompleted',taskData.isCompleted);

        if(taskData.isCompleted == 0){
          taskData.isCompleted = 1;
        }
        else{
          taskData.isCompleted = 0;

        }
      console.log('isCompleted',taskData.isCompleted);

      


      // return ;
            axios.post('/isCompleteTask',{
                isCompleted: taskData.isCompleted,
                id: taskData.id,
            }
        ).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            console.log(response.data.data)
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: response.data.message,
                    icon: 'EditIcon',
                    variant: 'success',
                  },
                })                
      this.fetchTasks()

            this.isSubmitting = false;
          } else {
            this.isSubmitting = false;
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: response.data.message,
                icon: 'AlertCircleIcon',
                variant: 'danger',
              },
            })
          }
        } else {
          this.isSubmitting = false

          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertCircleIcon',
              variant: 'danger',
              text: 'Something went wrong, try again later',
            },
          })
        }
      }).catch(error => {
        console.error(error)
      })

    },
    getAllTasks() {
      axios.get('/getAllTasks', {
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.tasks.value = response.data.data

            console.log('All task Fetched',this.tasks)
          } else {
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Error',
                icon: 'AlertCircleIcon',
                variant: 'danger',
                text: 'Something went wrong, try again later',
              },
            })
          }
        } else {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertCircleIcon',
              variant: 'danger',
              text: 'Something went wrong, try again later',
            },
          })
        }
      }).catch(error => {
        console.error(error)
      })
    },
  }
}
</script>

<style lang="scss" scoped>
.draggable-task-handle {
position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    visibility: hidden;
    cursor: move;

    .todo-task-list .todo-item:hover & {
      visibility: visible;
    }
}
</style>

<style lang="scss">
@import "~@core/scss/base/pages/app-todo.scss";
</style>

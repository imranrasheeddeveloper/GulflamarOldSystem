<template>
  <div>
    <b-sidebar
      id="sidebar-task-handler"
      sidebar-class="sidebar-lg"
      :visible="isTaskHandlerSidebarActive"
      bg-variant="white"
      shadow
      backdrop
      no-header
      right
      @change="(val) => $emit('update:is-task-handler-sidebar-active', val)"
      @hidden="clearForm"
    >
      <template #default="{ hide }">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <b-button
            v-if="taskLocal.id"
            size="sm"
            :variant="taskLocal.isCompleted ? 'outline-success' : 'outline-secondary'"
            @click="updateThisTaskCompleted(taskLocal); hide(); $emit('fetch-task');"
          >
            {{ taskLocal.isCompleted ? 'Completed' : 'Mark Complete' }}
          </b-button>
          <h5
            v-else
            class="mb-0"
          >
            Add Task
          </h5>
          <div>
            <feather-icon
              v-if="taskLocal.id && isOwner(taskLocal)"
              icon="TrashIcon"
              class="cursor-pointer"
              @click=" deleteTask(taskLocal); hide(); $emit('fetch-task');"
            />
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="XIcon"
              size="16"
              @click="hide"
            />
          </div>
        </div>

        <!-- Body -->
        <validation-observer
         
          ref="refFormObserver"
        >

          <!-- Form -->
          <b-form
            class="p-2"
            @submit.prevent
            @reset.prevent="reset_form"
          >

            <!-- Title -->
            <validation-provider
              #default="validationContext"
              name="Title"
              rules="required"
            >
              <b-form-group
                label="Title"
                label-for="task-title"
              >
                <b-form-input
                  id="task-title"
                  v-model="taskLocal.title"
                  autofocus
                  :state="getValidationState(validationContext)"
                  trim
                  placeholder="Task Title"
                />

                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!-- Assignee -->
            <b-form-group
              label="Assignee"
              label-for="assignee"
            >

              <v-select
                v-model="taskLocal.assignTo"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="AllAssignee"
                label="name"
                class="assignee-selector"
                input-id="assignee"
                :reduce="name => name.id" 
              >

                <template #option="{ name }">
                  <b-avatar
                    size="26"
                    :variant="`light-${resolveAvatarVariant(taskLocal.tag)}`"
                    :text="avatarText(name)"
                  />
                  <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
                </template>

                <template #selected-option="{ avatar, name }">
                  <b-avatar
                    size="26"
                    :src="avatar"
                    :variant="`light-${resolveAvatarVariant(taskLocal.tag)}`"
                    :text="avatarText(name)"
                  />

                  <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
                </template>
              </v-select>
            </b-form-group>

            <!-- due Date -->
            <validation-provider
              #default="validationContext"
              name="Due Date"
              rules="required"
            >

              <b-form-group
                label="Due Date"
                label-for="due-date"
              >
                <flat-pickr
                  v-model="taskLocal.dueDate"
                  class="form-control"
                />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!--Tag -->
            <b-form-group
              label="Tag"
              label-for="tag"
            >
              <v-select
                v-model="taskLocal.tag"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="tagOptions"
                :reduce="option => option.value"
                input-id="tags"
              />
            </b-form-group>

            <!-- Description -->
            <b-form-group
              label="Description"
              label-for="task-description"
            >
              <quill-editor
                id="quil-content"
                v-model="taskLocal.description"
                :options="editorOption"
                class="border-bottom-0"
              />
              <div
                id="quill-toolbar"
                class="d-flex justify-content-end border-top-0"
              >
                <!-- Add a bold button -->
                <button class="ql-bold" />
                <button class="ql-italic" />
                <button class="ql-underline" />
                <button class="ql-align" />
                <button class="ql-link" />
              </div>
            </b-form-group>

            <!-- Form Actions -->
            <div class="d-flex mt-2" v-if="taskLocal.id && isOwner(taskLocal)">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-2"
                type="submit"
                @click="submitTask(); hide(); $emit('fetch-task');"
                :disabled="isSubmitting"
              >
          <!-- <span v-if="isSubmitting">Please Wait</span> -->
          Update
              </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                variant="outline-secondary"
              >
                Reset
              </b-button>
            </div>


              <div class="d-flex mt-2" v-if="!taskLocal.id">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-2"
                type="submit"
                @click="submitTask(); hide(); $emit('fetch-task');"
                :disabled="isSubmitting"
              >
          <!-- <span v-if="isSubmitting">Please Wait</span> -->
                  Add
                </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                variant="outline-secondary"
              >
                Reset
              </b-button>
            </div>


          </b-form>
        </validation-observer>
      </template>
    </b-sidebar>
  </div>
</template>

<script>
import {
  BSidebar, BForm, BFormGroup, BFormInput, BAvatar, BButton, BFormInvalidFeedback,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, url } from '@validations'
import { avatarText } from '@core/utils/filter'
import formValidation from '@core/comp-functions/forms/form-validation'
import { toRefs } from '@vue/composition-api'
import { quillEditor } from 'vue-quill-editor'
import useTaskHandler from './useTaskHandler'
import axios from '@axios'  
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'



export default {
  components: {
    // BSV
    BButton,
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BAvatar,
    BFormInvalidFeedback,

    // 3rd party packages
    vSelect,
    flatPickr,
    quillEditor,

    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isTaskHandlerSidebarActive',
    event: 'update:is-task-handler-sidebar-active',
  },
  props: {
    isTaskHandlerSidebarActive: {
      type: Boolean,
      required: true,
    },
    task: {
      type: Object,
      required: true,
    },
    clearTaskData: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      isSubmitting: false,

      required,
      email,
      url,
      AllAssignee: [],

      formValues: {
        title: '',
        description: '',
        isCompleted: '',
        isDeleted: '',
        tag: '',
        isImportant: '',
        dueDate: '',
        name: '',
        type: '',





        

      },
    }
  },
    mounted() {
// this.formValues = this.taskLocal;
this.getAllAssignee();
    

  },
    methods: {

      isOwner(task){
        if(task.systemOwner == task.ownerId){
          return true;
        }
        return false;
      },
      

    reset_form() {
    console.log(this.formValues);

    Object.entries(this.formValues).forEach(([key, value]) => { {
          this.formValues[key] = '';
        }
      
    });
        Object.entries(this.taskLocal).forEach(([key, value]) => { {
          this.taskLocal[key] = '';
        }
      
    });

    console.log(this.formValues);
  },

    submitTask() {
      this.isSubmitting = true;

      this.formValues = this.taskLocal;
      console.log('formvalues', this.formValues);
      // return ;
      

      // this.formValues.supplier_types = this.items.map(a => a.type);
      // this.formValues.supplier_types = [...new Set(this.formValues.supplier_types)];

      axios.post('/addTask',
        this.formValues).then(response => {
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
                this.reset_form();
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


    deleteTask(task) {
      this.isSubmitting = true;
      console.log('delete', task.id);
      // return;
      

      // this.formValues = this.taskLocal;
      // console.log('formvalues', this.formValues);
      // // return ;
      

      // // this.formValues.supplier_types = this.items.map(a => a.type);
      // // this.formValues.supplier_types = [...new Set(this.formValues.supplier_types)];

      axios.post('/deleteTask',
        {id:task.id}).then(response => {
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
                this.reset_form();
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


    getAllAssignee() {
      axios.get('/getAllAssignee', {
      }).then(response => {
        if (response.data.hasOwnProperty('success')) {
          if (response.data.success === true) {
            /* console.log(response.data.data); */
            this.AllAssignee = response.data.data

            console.log('All Assignees Fetched')
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




    },
  setup(props, { emit }) {
    const {
      taskLocal,
      resetTaskLocal,

      // UI
      assigneeOptions,
      onSubmit,
      tagOptions,
      resolveAvatarVariant,
    } = useTaskHandler(toRefs(props), emit)

    const {
      refFormObserver,
      getValidationState,
      resetForm,
      clearForm,
    } = formValidation(resetTaskLocal, props.clearTaskData)

    const editorOption = {
      modules: {
        toolbar: '#quill-toolbar',
      },
      placeholder: 'Write your description',
    }

    return {
      // Add New
      taskLocal,
      onSubmit,
      assigneeOptions,
      tagOptions,

      // Form Validation
      resetForm,
      clearForm,
      refFormObserver,
      getValidationState,

      // UI
      editorOption,
      resolveAvatarVariant,

      // Filter/Formatter
      avatarText,
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
@import '@core/scss/vue/libs/quill.scss';
</style>

<style lang="scss" scoped>
@import '~@core/scss/base/bootstrap-extended/include';

.assignee-selector {
  ::v-deep .vs__dropdown-toggle {
  padding-left: 0;
  }
}

#quil-content ::v-deep {
  > .ql-container {
    border-bottom: 0;
  }

  + #quill-toolbar {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: $border-radius;
    border-bottom-right-radius: $border-radius;
  }
}
</style>

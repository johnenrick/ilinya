<template>
    <div class="col-lg-4 offset-lg-4 custom-container">
      <div class="header-title">
        <i class="fa fa-home" aria-hidden="true" v-on:click="redirect()"></i>
        <span>Company Registration</span>
      </div>
      <span class="text-danger error-holder text-center" v-if="errorStatus !== ''">
        <label>{{errorStatus}}</label>
      </span>
       <form ref="registration">
        <input-group
          :inputs="inputs" :form_status="'create'" :error_list="errorList"
        ></input-group>
      </form>
        <button class="btn btn-primary pull-right" v-on:click="register()">Register</button>
    </div>
</template>
<script>
import ROUTER from '../../router'
export default {
  name: '',
  components: {
    'input-group': require('components/input_field/InputGroup.vue')
  },
  mounted(){
  },
  data(){
    return{
      inputs: {
        business_type_id: {
          label_colspan: 0,
          input_type: 'select',
          input_setting: {
            options: [{
              value: 1,
              label: 'Call Center'
            }]
          }
        },
        name: {
          label_colspan: 0,
          db_name: 'company_branches[name]',
          placeholder: 'Company Name'
        },
        code: {
          label_colspan: 0,
          db_name: 'company_branches[code]',
          placeholder: 'Company Code'
        },
        email: {
          label_colspan: 0,
          db_name: 'company_branches[email]',
          placeholder: 'Company Email'
        },
        address: {
          label_colspan: 0,
          db_name: 'company_branches[address]',
          placeholder: 'Company Address'
        },
        contact_number: {
          label_colspan: 0,
          db_name: 'company_branches[contact_number]',
          placeholder: 'Company Contact Number'
        }
      },
      errorList: {},
      errorStatus: ''
    }
  },
  methods: {
    register(){
      this.APIFormRequest('company/create', this.$refs.registration, response => {
        if(response.error.status === 100){
          this.errorStatus = ('company_branches.email' in response.error.message && !('company_branches.name' in response.error.message)) ? 'Email Address already taken.' : 'Please fill up the required informations.'
          console.log(response.error.message['company_branches.name'])
        }else if(response.error.status !== 'undefined'){
          this.redirect()
        }
      })
    },
    redirect(){
      ROUTER.push('/')
    }
  }
}
</script>
<style>
  .error-holder label{
    padding: 5px 0 5px 0;
  }
</style>

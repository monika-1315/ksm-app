<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Zarejestruj się</div>
          <div class="card-body">
            <div class="alert alert-danger" v-if="has_error && !success">
                <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                <p v-else>Error, can not register at the moment. If the problem persists, please contact an administrator.</p>
            </div>
            <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                    <label for="name">Imię</label>
                    <input type="text" id="name" class="form-control" placeholder="Imię" v-model="name">
                    <span class="help-block" v-if="has_error && errors.name">{{ errors.name }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                    <label for="surname">Nazwisko</label>
                    <input type="text" id="surname" class="form-control" placeholder="Nazwisko" v-model="surname">
                    <span class="help-block" v-if="has_error && errors.name">{{ errors.name }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.email }">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email">
                    <span class="help-block" v-if="has_error && errors.email">{{ errors.email }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password">Hasło</label>
                    <input type="password" id="password" class="form-control" v-model="password">
                    <span class="help-block" v-if="has_error && errors.password">{{ errors.password }}</span>
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                    <label for="password_confirmation">Powtórz hasło</label>
                    <input type="password" id="password_confirmation" class="form-control" v-model="password_confirmation">
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error }">
                    <label for="birthdate">Data urodzenia</label>
                    <input type="date" id="birthdate" class="form-control" v-model="birthdate">
                </div>
                <div class="form-group" v-bind:class="{ 'has-error': has_error }">
                    <label for="division">Oddział</label><br>
                    <input type="radio" name="div" value="0" checked> Bolesławiec<br>
                    <input type="radio" name="div" value="1" > Lubin<br>
                    <input type="radio" name="div" value="2" > Legnica<br>
                    <input type="radio" name="div" value="3" > Gościszów<br>
                    
                </div>
                <button type="submit" class="btn btn-primary">Zarejestruj się</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        name: '',
        surname: '',
        email: '',
        password: '',
        password_confirmation: '',
        birthdate: '',
        // division: '',
        has_error: false,
        error: '',
        errors: {},
        success: false
      }
    },
    methods: {
      register() {
        var app = this
        this.$auth.register({
          data: {
            name: app.name,
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          },
          success: function () {
            app.success = true
            this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
          },
          error: function (res) {
            // console.log(res.response.data.errors)
            app.has_error = true
            app.error = res.response.data.error
            app.errors = res.response.data.errors || {}
          }
        })
      }
    }
  }
</script>
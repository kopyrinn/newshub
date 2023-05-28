<template>
  <div class="card mb-5 mb-xl-10">
    <div
      class="card-header border-0 cursor-pointer"
      role="button"
      data-bs-toggle="collapse"
      data-bs-target="#kt_account_profile_details"
      aria-expanded="true"
      aria-controls="kt_account_profile_details"
    >
      <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ $t('Profile details') }}</h3>
      </div>
    </div>
    <div id="kt_account_profile_details" class="collapse show">
      <Form
        id="kt_account_profile_details_form"
        class="form"
        novalidate="novalidate"
        @submit="saveChanges()"
        :validation-schema="profileDetailsValidator"
      >
        <div class="card-body border-top p-9">
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $t('Avatar') }}</label>
            <div class="col-lg-8">
              <div
                class="image-input"
                data-kt-image-input="true"
                v-bind:style="{ backgroundImage: 'url(' + $storage(blankImage) + ')' }"
              >
                <div
                  id="previewAvatar"
                  class="image-input-wrapper w-125px h-125px"
                  v-bind:style="{
                    backgroundImage: 'url(' + $storage(user.avatar) + ')',
                    zIndex: 1,
                  }"
                ></div>
                <label
                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                  data-kt-image-input-action="change"
                  data-bs-toggle="tooltip"
                  title="Change avatar"
                  style="z-index: 1"
                >
                  <i class="bi bi-pencil fs-7"></i>
                  <input type="file" @input="pickFile" accept=".png, .jpg, .jpeg" />
                </label>
                <span
                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                  data-kt-image-input-action="remove"
                  data-bs-toggle="tooltip"
                  @click="removeImage()"
                  title="Remove avatar"
                  style="z-index: 1"
                >
                  <i class="bi bi-x fs-2"></i>
                </span>
              </div>
              <div class="form-text">{{ $t('Extensions') }} : png, jpg, jpeg.</div>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $root.user.is_journalist? $t('Name'):  $t('Organization name') }}</label>
            <div class="col-lg-8">
              <Field
                type="text"
                name="name"
                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                :placeholder="$root.user.is_journalist? $t('Name'):  $t('Organization name')"
                v-model="user.name"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="name" />
                </div>
              </div>
            </div>
          </div>
          <div v-if="$root.user.is_journalist" class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $t('Last Name') }}</label>
            <div class="col-lg-8">
              <Field
                type="text"
                name="lastname"
                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                :placeholder="$t('Last Name')"
                v-model="user.lastname"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="lastname" />
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $t('Phone') }}</label>
            <div class="col-lg-8">
              <Field
                type="text"
                name="phone"
                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                :placeholder="$t('Phone')"
                v-model="user.phone"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="phone" />
                </div>
              </div>
            </div>
          </div>


          <div v-if="user.is_journalist" class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $t('City') }}</label>
            <div class="col-lg-8">
              <select class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" :placeholder="$t('City')" v-model="user.city_id">
                  <option value="">{{ $t('Select city') }}</option>
                  <option v-for="city in cities" :value="city.id">{{ city['city_name_' + $root.locale] }}</option>
              </select>
            </div>
          </div>
          <div v-else class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $t('Category') }}</label>
            <div class="col-lg-8">
              <select class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" :placeholder="$t('City')" v-model="user.user_category_id">
                  <option value="">{{ $t('Select category') }}</option>
                  <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
              </select>
            </div>
          </div>
<!-- 
          <div  class="form-floating mb-6">
              <label class="form-label required">{{ $t('City') }}</label>
              <div v-if="errors.city_id && errors.city_id.length" class="fv-plugins-message-container invalid-feedback d-block">
                  <span v-for="(error, index) in errors.city_id" v-bind:key="index">{{ error }}</span>
              </div>
          </div>
          <div class="form-floating mb-6">
              <select class="form-control form-control-lg form-control-solid" :placeholder="$t('Category')" v-model="form.user_category_id">
                  <option value="">{{ $t('') }}</option>
                  <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
              </select>
              <label class="form-label required">{{ $t('Category') }}</label>
              <div v-if="errors.user_category_id && errors.user_category_id.length" class="fv-plugins-message-container invalid-feedback d-block">
                  <span v-for="(error, index) in errors.user_category_id" v-bind:key="index">{{ error }}</span>
              </div>
          </div> -->

          <!-- <div class="row mb-0">
            <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $t('Allow email newsletter') }}</label>
            <div class="col-lg-8 d-flex align-items-center">
              <div class="form-check form-check-solid form-switch fv-row">
                <input
                  class="form-check-input w-45px h-30px"
                  type="checkbox"
                  v-model="user.newsletter"
                  id="newsletter"
                />
                <label class="form-check-label" for="newsletter"></label>
              </div>
            </div>
          </div> -->
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <button
            type="submit"
            id="kt_account_profile_details_submit"
            ref="submitButton1"
            class="btn btn-primary"
          >
            <span class="indicator-label"> {{ $t('Save') }} </span>
            <span class="indicator-progress">
              {{ $t('Please, wait') }}...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </Form>
    </div>
  </div>
  <div class="card mb-5 mb-xl-10">
    <div
      class="card-header border-0 cursor-pointer"
      role="button"
      data-bs-toggle="collapse"
      data-bs-target="#kt_account_signin_method"
    >
      <div class="card-title m-0">
        <h3 class="fw-boldest m-0">{{ $t('Account') }}</h3>
      </div>
    </div>
    <div id="kt_account_signin_method" class="collapse show">
      <div class="card-body border-top p-9">
        <div class="d-flex flex-wrap align-items-center mb-8">
          <div id="kt_signin_email" :class="{ 'd-none': emailFormDisplay }">
            <div class="fs-4 fw-boldest mb-1">Email</div>
            <div class="fs-6 fw-bold text-gray-600">{{ user.email }}</div>
          </div>

          <div
            id="kt_signin_email_edit"
            :class="{ 'd-none': !emailFormDisplay }"
            class="flex-row-fluid"
          >
            <Form
              id="kt_signin_change_email"
              class="form"
              novalidate="novalidate"
              @submit="updateEmail()"
              :validation-schema="changeEmail"
            >
              <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                  <div class="fv-row mb-0">
                    <label for="emailaddress" class="form-label fs-6 fw-bolder mb-3"
                      >Email</label
                    >
                    <Field
                      type="email"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      id="emailaddress"
                      placeholder="Email"
                      name="emailaddress"
                      autocomplete="off"
                      v-model="user.email"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="emailaddress" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="fv-row mb-0">
                    <label
                      for="confirmemailpassword"
                      class="form-label fs-6 fw-bolder mb-3"
                      >{{ $t('Password') }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="confirmemailpassword"
                      autocomplete="off"
                      v-model="confirmEmailPassword"
                      id="confirmemailpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="confirmemailpassword" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex">
                <button
                  id="kt_signin_submit"
                  type="submit"
                  ref="updateEmailButton"
                  class="btn btn-primary me-2 px-6"
                >
                  <span class="indicator-label"> {{ $t('Update') }} Email </span>
                  <span class="indicator-progress">
                    {{ $t('Please, wait') }}...
                    <span
                      class="spinner-border spinner-border-sm align-middle ms-2"
                    ></span>
                  </span>
                </button>
                <button
                  id="kt_signin_cancel"
                  type="button"
                  class="btn btn-color-gray-400 btn-active-light-primary px-6"
                  @click="emailFormDisplay = !emailFormDisplay"
                >
                  {{ $t('Cancel') }}
                </button>
              </div>
            </Form>
          </div>
          <div
            id="kt_signin_email_button"
            :class="{ 'd-none': emailFormDisplay }"
            class="ms-auto"
          >
            <button
              class="btn btn-light fw-boldest px-6"
              @click="emailFormDisplay = !emailFormDisplay"
            >
              {{ $t('Change') }} Email
            </button>
          </div>
        </div>
        <div class="d-flex flex-wrap align-items-center mb-8">
          <div id="kt_signin_password" :class="{ 'd-none': passwordFormDisplay }">
            <div class="fs-4 fw-boldest mb-1">{{ $t('Password') }}</div>
            <div class="fs-6 fw-bold text-gray-600">************</div>
          </div>
          <div
            id="kt_signin_password_edit"
            class="flex-row-fluid"
            :class="{ 'd-none': !passwordFormDisplay }"
          >
            <div class="fs-6 fw-bold text-gray-600 mb-4">
              {{ $t('Password must be at least 8 character and contain symbols') }}
            </div>
            <Form
              id="kt_signin_change_password"
              class="form"
              novalidate="novalidate"
              @submit="updatePassword()"
              :validation-schema="changePassword"
            >
              <div class="row mb-6">
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label for="currentpassword" class="form-label fs-6 fw-bolder mb-3"
                      >{{ $t('Current password') }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="currentpassword"
                      v-model="changePasswordData.currentpassword"
                      id="currentpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="currentpassword" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label for="newpassword" class="form-label fs-6 fw-bolder mb-3"
                      >{{ $t('New password') }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="newpassword"
                      v-model="changePasswordData.newpassword"
                      id="newpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="newpassword" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label for="confirmpassword" class="form-label fs-6 fw-bolder mb-3"
                      >{{ $t('Confirm new password') }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="confirmpassword"
                      v-model="changePasswordData.confirmnewpassword"
                      id="confirmpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="confirmpassword" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex">
                <button
                  id="kt_password_submit"
                  type="submit"
                  ref="updatePasswordButton"
                  class="btn btn-primary me-2 px-6"
                >
                  <span class="indicator-label"> {{ $t('Update password') }} </span>
                  <span class="indicator-progress">
                    {{ $t('Please, wait') }}...
                    <span
                      class="spinner-border spinner-border-sm align-middle ms-2"
                    ></span>
                  </span>
                </button>
                <button
                  id="kt_password_cancel"
                  type="button"
                  @click="passwordFormDisplay = !passwordFormDisplay"
                  class="btn btn-color-gray-400 btn-active-light-primary px-6"
                >
                  {{ $t('Cancel') }}
                </button>
              </div>
            </Form>
          </div>
          <div
            id="kt_signin_password_button"
            class="ms-auto"
            :class="{ 'd-none': passwordFormDisplay }"
          >
            <button
              @click="passwordFormDisplay = !passwordFormDisplay"
              class="btn btn-light fw-boldest"
            >
              {{ $t('Reset Password') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="card mb-5 mb-xl-10"> -->
    <!-- <div
      class="card-header border-0 cursor-pointer"
      role="button"
      data-bs-toggle="collapse"
      data-bs-target="#kt_account_deactivate"
      aria-expanded="true"
      aria-controls="kt_account_deactivate"
    >
      <div class="card-title m-0">
        <h3 class="fw-boldest m-0">Управление аккаунтом</h3>
      </div>
    </div> -->
    <!-- <div id="kt_account_deactivate" class="collapse show">
      <form
        id="kt_account_deactivate_form"
        class="form"
        @submit.prevent="deactivateAccount()"
      >
        <div class="card-body border-top p-9">
          <div
            class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6"
          >
            <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
              <inline-svg src="/assets/media/icons/duotune/general/gen044.svg" />
            </span>
            <div class="d-flex flex-stack flex-grow-1">
              <div class="fw-bold">
                <h4 class="text-gray-800 fw-bolder">You Are Deactivating Your Account</h4>

                <div class="fs-6 text-gray-600">
                  For extra security, this requires you to confirm your email or phone
                  number when you reset yousignr password. <br /><a
                    class="fw-bolder"
                    href="javascript://"
                    >Подробнее</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="form-check form-check-solid fv-row">
            <input
              name="deactivate"
              class="form-check-input"
              type="checkbox"
              value=""
              id="deactivate"
            />
            <label class="form-check-label fw-bold ps-2 fs-6" for="deactivate"
              >Подтвердите деактивацию аккаунта</label
            >
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <button
            id="kt_account_deactivate_account_submit"
            ref="submitButton5"
            type="submit"
            class="btn btn-danger fw-bold"
          >
            <span class="indicator-label"> Деактивировать аккаунт </span>
            <span class="indicator-progress">
              Пожалуйста подождите...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div> -->
  <!-- </div> -->
</template>

<script>
import { defineComponent } from "vue";
import { ErrorMessage, Field, Form } from "vee-validate";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";
import showErrors from "@/helpers/notify";
import {
  ElLoading,
  ElNotification
} from 'element-plus'

export default defineComponent({
  name: "account-settings",
  components: {
    ErrorMessage,
    ElLoading,
    ElNotification,
    Field,
    Form,
  },
  data() {
    return {
      emailFormDisplay: false,
      passwordFormDisplay: false,
      confirmEmailPassword: "",
      changePasswordData: {
        currentpassword: "",
        newpassword: "",
        confirmnewpassword: "",
      },
      profileDetailsValidator: null,
      changeEmail: null,
      changePassword: null,
      blankImage: "avatar.jpg",
      user: {...this.$root.user},
      categories: [],
      cities: [],
    }
  },
  created() {
    if (this.$route.meta.noSsr && import.meta.env.SSR) return false

    this.$get('fields').then(({data}) => {
        this.categories = data.categories
        this.cities = data.cities
    }).catch((e) => {})

    this.profileDetailsValidator = Yup.object().shape({
      name: Yup.string().required().label("Имя"),
    });

    this.changeEmail = Yup.object().shape({
      emailaddress: Yup.string().required().email().label("Email"),
      confirmemailpassword: Yup.string().required().label("Пароль"),
    });

    this.changePassword = Yup.object().shape({
      currentpassword: Yup.string().required().label("Текущий пароль"),
      newpassword: Yup.string().min(4).required().label("Пароль"),
      confirmpassword: Yup.string()
        .min(4)
        .required()
        .oneOf([Yup.ref("newpassword"), null], "Пароли должны совпадать")
        .label("Подтверждение пароля"),
    });
  },
  methods: {
    saveChanges() {
      if (this.$refs.submitButton1) {
        this.$refs.submitButton1.setAttribute("data-kt-indicator", "true");
        const {name, avatar, lastname, phone, city_id, user_category_id} = this.user;

        this.$api("account/settings", true, {
          method: 'POST',
          data: {name, avatar, lastname, phone, city_id, user_category_id}
        })
        .then(({ data }) => {
          this.$store.commit('setUser', data.user)
          this.user = {...data.user}

          ElNotification({
            type: 'success',
            title: this.$t('Notification'),
            message: `Настройки аккаунта обновлены`,
            duration: 2000,
          })
          this.$refs.submitButton1.removeAttribute("data-kt-indicator");
        })
        .catch(({ response }) => {
          showErrors(response);
          this.$refs.submitButton1.removeAttribute("data-kt-indicator");
        });
      }
    },
    deactivateAccount() {
      if (this.$refs.submitButton5) {
        // Activate indicator
        this.$refs.submitButton5.setAttribute("data-kt-indicator", "true");

        setTimeout(() => {
          this.$refs.submitButton5.removeAttribute("data-kt-indicator");

          Swal.fire({
            text: "Email успешно изменен!",
            icon: "success",
            confirmButtonText: "Ок",
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-light-primary",
            },
          }).then(() => {
            emailFormDisplay.value = false;
          });
        }, 2000);
      }
    },
    updateEmail() {
      if (this.$refs.updateEmailButton) {
        this.$refs.updateEmailButton.setAttribute("data-kt-indicator", "true");

        this.$api("account/email", true, {
          method: 'POST',
          data: { email: this.user.email, password: this.confirmEmailPassword }
        })
          .then(({ data }) => {
              this.$store.commit('setUser', data.user)
              this.user = {...data.user}

            ElNotification({
              type: 'success',
              title: this.$t('Notification'),
              message: `Email обновлен`,
              duration: 2000,
            })
            this.$refs.updateEmailButton.removeAttribute("data-kt-indicator");
            emailFormDisplay.value = false;
          })
          .catch(({ response }) => {
            showErrors(response);
            this.$refs.updateEmailButton.removeAttribute("data-kt-indicator");
          });
      }
    },
    updatePassword() {
      if (this.$refs.updatePasswordButton) {
        this.$refs.updatePasswordButton.setAttribute("data-kt-indicator", "true");

        this.$api("account/password", true, {
          method: 'POST',
          data: changePasswordData.value
        })
          .then(({ data }) => {
            ElNotification({
              type: 'success',
              title: this.$t('Notification'),
              message: `Пароль обновлен`,
              duration: 2000,
            })
            this.$refs.updatePasswordButton.removeAttribute("data-kt-indicator");
            passwordFormDisplay.value = false;
            changePasswordData.value.currentpassword = "";
            changePasswordData.value.newpassword = "";
            changePasswordData.value.confirmnewpassword = "";
          })
          .catch(({ response }) => {
            showErrors(response);
            this.$refs.updatePasswordButton.removeAttribute("data-kt-indicator");
          });
      }
    },
    removeImage() {
      this.user.avatar = this.blankImage;
    },
    pickFile(e) {
      if (e.target.files.length) {
        let loadingInstance = ElLoading.service({
          target: "#previewAvatar",
        });

        let formData = new FormData();
        formData.append('image', e.target.files[0]);

        this.$api("image/rectangle", true, {
          method: 'POST',
          data: formData
        })
          .then(({ data }) => {
            loadingInstance.close()

            if (!data.ok) return false

            this.user.avatar = data.images.lg;
          })
          .catch(({ response }) => {
            showErrors(response);
            loadingInstance.close()
          });
      }
    },
  }
});
</script>

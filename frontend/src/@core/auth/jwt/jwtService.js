import Vue from 'vue'
import { initialAbility } from '@/libs/acl/config'
import router from '@/router'
import axios from 'axios'
import jwtDefaultConfig from './jwtDefaultConfig'

export default class JwtService {
  // Will be used by this service for making API calls
  axiosIns = null

  // jwtConfig <= Will be used by this service
  jwtConfig = { ...jwtDefaultConfig }

  // For Refreshing Token
  isAlreadyFetchingAccessToken = false

  // For Refreshing Token
  subscribers = []

  constructor(axiosIns, jwtOverrideConfig) {
    this.axiosIns = axiosIns
    this.jwtConfig = { ...this.jwtConfig, ...jwtOverrideConfig }

    // Request Interceptor
    this.axiosIns.interceptors.request.use(
      config => {
        // Get token from localStorage
        const accessToken = this.getToken()

        // If token is present add it to request's Authorization Header
        if (accessToken) {
          // eslint-disable-next-line no-param-reassign
          config.headers.Authorization = `${this.jwtConfig.tokenType} ${accessToken}`
          // config.headers.Content-Type = `multipart/form-data`
        }
        return config
      },
      error => Promise.reject(error),
    )

    // Add request/response interceptor
    this.axiosIns.interceptors.response.use(
      response => response,
      error => {
        // const { config, response: { status } } = error
        const { config, response } = error
        const originalRequest = config

        if (!this.isAlreadyFetchingAccessToken) {
          this.isAlreadyFetchingAccessToken = true
          this.refreshToken().then(r => {
            this.isAlreadyFetchingAccessToken = false

            // Update accessToken in localStorage
            this.setToken(r.data.access_token)
            this.setRefreshToken(r.data.access_token)

            this.onAccessTokenFetched(r.data.access_token)
          })
        }

        // if (status === 401) {
        if (response && response.status === 401) {
          localStorage.removeItem(this.jwtConfig.storageTokenKeyName)
          localStorage.removeItem(this.jwtConfig.storageRefreshTokenKeyName)

          // Remove userData from localStorage
          localStorage.removeItem('userData')

          // Reset ability
          // this.$ability.update(initialAbility)

          // Redirect to login page
          this.$router.push({ name: 'auth-login' })

          /* const retryOriginalRequest = new Promise(resolve => {
            this.addSubscriber(accessToken => {
              // Make sure to assign accessToken according to your response.
              // Check: https://pixinvent.ticksy.com/ticket/2413870
              // Change Authorization header
              originalRequest.headers.Authorization = `${this.jwtConfig.tokenType} ${accessToken}`
              resolve(this.axiosIns(originalRequest))
            })
          })
          return retryOriginalRequest */
        }
        return Promise.reject(error)
      },
    )
  }

  onAccessTokenFetched(accessToken) {
    this.subscribers = this.subscribers.filter(callback => callback(accessToken))
  }

  addSubscriber(callback) {
    this.subscribers.push(callback)
  }

  getToken() {
    return localStorage.getItem(this.jwtConfig.storageTokenKeyName)
  }

  getRefreshToken() {
    return localStorage.getItem(this.jwtConfig.storageRefreshTokenKeyName)
  }

  setToken(value) {
    localStorage.setItem(this.jwtConfig.storageTokenKeyName, value)
  }

  setRefreshToken(value) {
    localStorage.setItem(this.jwtConfig.storageRefreshTokenKeyName, value)
  }

  login(...args) {
    return this.axiosIns.post(this.jwtConfig.loginEndpoint, ...args)
  }

  setPermissions() {
    axios.get('/getPermissions')

      .then(response => {
        console.log('response', response.data.data)
        const user = response.data.data
        const userData = user
        this.$ability.update(userData.ability)
      }).catch(error => {
        console.error(error)
      })
  }

  register(...args) {
    return this.axiosIns.post(this.jwtConfig.registerEndpoint, ...args)
  }

  refreshToken() {
    return this.axiosIns.post(this.jwtConfig.refreshEndpoint, {
      refreshToken: this.getRefreshToken(),
    })
  }
}

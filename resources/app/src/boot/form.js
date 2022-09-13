
let form = {

    getFormErrors(form, key){
        let e = form.errors && form.errors.errors && form.errors.errors[key];
        return e ? e : []
    },

    getFormError(form, key){
        return this.getFormErrors(form, key).length ? true : false
    },

    getError(e=[], key=null, bool=false){
        if(!e) return bool ? false : ''

        if(key in e)
          if(e[key].length)
            return bool ? true : e[key][0]

        return bool ? false : ''
    },

}


export default async ( { app, store, router, Vue } ) => {
  app.config.globalProperties.$form = form
}

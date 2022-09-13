import moment from 'moment'

let util = {
    data: {
    loader: false
    },

    copy(inputObject){
        let obj = Object.assign({}, inputObject)
        let x = JSON.parse(JSON.stringify(obj))
        return x
    },

    getFormatedPrice (value) {
        return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value)
    },

    getDate (val, format='DD.MM.Y', fb='-') {
        if (!val) return fb
        return moment(val).format(format)
    },

    startLoader () {
        if (!this.data.loader) { this.data.loader = Vue.$loading.show() }
    },

    stopLoader () {
        this.data.loader.hide()
        this.data.loader = false
    },

    randomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    },

    buildURLQuery(obj){
        let url = Object.entries(obj)
                .map(pair => pair.map(encodeURIComponent).join('='))
                .join('&');

        return url
    },

    isError(form, name){
        return this.getError(form, name).length == 0 ? false: true
    },

    getError(form, name){
        return form.errors.has(name) ? form.errors.errors[name][0] : ''
    },

    is(i, yes=true, no=false){
        return i ? yes : no
    },


    // Map Operations
    getMapURL(a, o = {}){
        if(!a) return false;

        let x = "https://maps.google.com/maps?hl=en&q=__adress&ie=UTF8&t=&z=10&iwloc=B&output=embed&zoomControl=false"
        x = x.replace('z=10', 'z=' + (o.zoom ? o.zoom : 15) )
        x = x.replace('hl=en', 'hl=' + (o.hl ? o.hl : 'de') )
        x = x.replace('__adress',encodeURI(a))
        return x
    },

    generateMapAdressURL(l, o = {}){
        if(l.mapURL) return l.mapURL
        let a = this.generateMapAdress(l)
        return this.getMapURL(a, o)
    },

    generateMapAdress(l){
        let a = l.address
        if(l.postcode)  a += ', '+l.postcode
        if(l.city)      a += ' '+l.city
        return a
    },

    getUserOrderName(user){
        let fb = user.displayname

        if(user.profile){
            return user.profile.firstname + ' ' + user.profile.lastname
        }

        return fb
    },

    generateAdress(l, coords=false){
        if(!l) return ''

        let x = ''

        if(l.address)
        x += l.address + '<br>'

        if(l.postcode)
        x += l.postcode + ' '

        if(l.city)
        x += l.city + '<br>'

        if((l.lat || l.lng ) && coords)
        x += l.lat + ' ' + l.lng

        return x
    },

    objectToFormData(obj, form, namespace) {
        var fd = form || new FormData();
        var formKey;

        for(var property in obj) {
        if(obj.hasOwnProperty(property)) {

            if(namespace) {
            formKey = namespace + '[' + property + ']';
            } else {
            formKey = property;
            }

            // if the property is an object, but not a File,
            // use recursivity.
            if(typeof obj[property] === 'object' && !(obj[property] instanceof File)) {

            this.objectToFormData(obj[property], fd, property);

            } else {

            // if it's a string or a File object
            fd.append(formKey, obj[property]);
            }

        }
        }

        return fd;
    },

    // active boolean operations
    getActiveColor(i, active='grey-8', fb='grey-4'){
        return this.get(i, true, active, fb)
    },

    getIcon(item, a,b){
        return this.get(item, true, a, b)
    },

    getIconColor(item,active,fb){
        return this.get(item,true,active,fb)
    },

    getSelectedColor(item){
        let is = item !== null ? true : item
        return this.get(item, is)
    },

    get(item, is=true, active='grey-8', fb='grey-4'){
        if(item === null) return fb
        return item == is ? active : fb
    },


    moveToUser(user){
        router.push({ name: 'user.show', params: { user: user.username } })
    },

    isVote(vote, type){
        if(!vote) return false

        if(vote && vote.vote)
        return vote.vote === type
    },

    getVoteCountColor(vote){
        return parseInt(vote) >= 0 ? 'text-green-8' : 'text-pink-9'
    },

    getIsVotedColor(vote, type){
        let voted = this.isVote(vote, type)
        return voted ? 'text-deep-orange-4' : ''
    },


    haveItem(item, key){
        return item && item[key]
    },

    isFavorite(item){
        return this.haveItem(item, 'userFavorite')
    },

    getFavoriteIcon(item){
        let i = this.isFavorite(item)
        return i ? 'favorite' : 'favorite_border'
        return i ? 'bookmark' : 'bookmark_border'
    },

    getIsFavoriteColor(item){
        let i = this.isFavorite(item)
        return i ? 'red-4' : ''
        return i ? 'green-4' : ''
    },

    isLiked(item){
        return this.haveItem(item, 'userLike')
    },

    getIsLikedColor(item){
        let i = this.isLiked(item)
        return i ? 'red-4' : ''
    },

    getLikedIcon(item){
        let i = this.isLiked(item)
        return i ? 'favorite' : 'favorite_border'
    },

    getSortIcon(sort){
        if(sort === null) return 'fa fa-sort'

        return sort ? 'fa fa-sort-down' : 'fa fa-sort-up'
    },

    formatBytes(bytes, decimals = 2) {
        if(!bytes) return 'n/a';
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },

    createImageURL(file){
        if(!file) return ''
        return URL.createObjectURL(file)
    },  

}

export default async ( { app, store, router, Vue } ) => {
  app.config.globalProperties.$util = util
}

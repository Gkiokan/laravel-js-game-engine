import moment from 'moment'

let helper = {
    data: {
      loader: false
    },

    roles: [
        { value: 'superadmin', label: 'Super Admin' },
        { value: 'admin', label: 'Admin' },
        { value: 'user', label: 'User' },
        { value: 'upper', label: 'Upper' },
    ],

    typeOptions: [
      { label: 'Film', value: 'movie' },
      { label: 'Serie', value: 'series' },      
      { label: 'Anime', value: 'anime' },
      { label: 'Spiele', value: 'game' },      
      { label: 'Dokus', value: 'doku' },
      { label: 'Digitale Medien', value: 'digital' },
      // { label: 'ABooks', value: 'abooks' },
      // { label: 'EBooks', value: 'ebooks' },      
      // { label: 'AE Books', value: 'book' },      
      // { label: 'Doku (show)', value: 'doku_show' },
      // { label: 'Doku (movies)', value: 'doku_movies' },
      // { label: 'Anime (show)', value: 'anime_show' },
      // { label: 'Anime (movies)', value: 'anime_movies' },      
      // { label: 'Comic', value: 'comic' },
      { label: 'Musik', value: 'music' },
  ],

  subTypeOptions: [
      { label: "Film", value: 'movie' },
      { label: "Serie", value: 'serie' },
      { label: "A Book", value: 'abook' },
      { label: "E Book", value: 'ebook' },
      { label: "Comic", value: 'comic' },      
  ],

  subTypes: {
    movie: [
      { label: "Film", value: 'movie' },
      { label: "Serie", value: 'serie' },      
    ],
    digital: [
      { label: "A Book", value: 'abook' },
      { label: "E Book", value: 'ebook' },
      { label: "Comic", value: 'comic' },
    ],
    game: [
      { label: 'Multi', value: 'multi' },
      { label: 'NintendoDS', value: 'NintendoDS' },
      { label: 'PCGames', value: 'PCGames' },
      { label: 'PlayStation3', value: 'PlayStation3' },
      { label: 'PlayStation4', value: 'PlayStation4' },
      { label: 'PlayStation5', value: 'PlayStation5' },
      { label: 'Wii', value: 'Wii' },
      { label: 'WiiU', value: 'WiiU' },
      { label: 'Xbox360', value: 'Xbox360' },
      { label: 'XboxOne', value: 'XboxOne' },
    ]
  },

  gameOptions: [      
      { label: 'NintendoDS', value: 'NintendoDS' },
      { label: 'PCGames', value: 'PCGames' },
      { label: 'PlayStation3', value: 'PlayStation3' },
      { label: 'PlayStation4', value: 'PlayStation4' },
      { label: 'PlayStation5', value: 'PlayStation5' },
      { label: 'Wii', value: 'Wii' },
      { label: 'WiiU', value: 'WiiU' },
      { label: 'Xbox360', value: 'Xbox360' },
      { label: 'XboxOne', value: 'XboxOne' },
  ],

  genreOptions: [
      { label: 'Action', value: 'action' },
      { label: 'Abenteuer', value: 'adventure' },
      { label: 'Animation', value: 'animation' },
      { label: 'Komoddie', value: 'comedy' },
      { label: 'Krimi', value: 'crime' },
      { label: 'Documentation', value: 'documentation' },
      { label: 'Drama', value: 'drama' },
      { label: 'Eastern', value: 'eastern' },
      { label: 'Erotic', value: 'erotic' },
      { label: 'Fantsy', value: 'fantasy' },
      { label: 'Historical', value: 'history' },
      { label: 'Horror', value: 'horror' },
      { label: 'Mystery', value: 'mystery' },
      { label: 'Musical', value: 'musical' },
      { label: 'Romance', value: 'romance' },
      { label: 'Science Fiction', value: 'sci-fi' },
      { label: 'Thriller', value: 'thriller' },
      { label: 'Kriegsfilm', value: 'war' },
      { label: 'Western', value: 'western' },
      { label: 'XXX', value: 'xxx' },
      { label: 'ebooks', value: 'ebooks' },
      { label: 'Anime', value: 'anime' },
      { label: 'Sport', value: 'sport' },
      { label: 'Reality-TV', value: 'realitytv' },
  ],
  
  sortByOptions: [
      { label: 'Year', value: 'year' },
      { label: 'Created', value: 'created' },
      { label: 'Released', value: 'released' },
      { label: 'Rating', value: 'rating' },
      { label: 'Trends', value: 'trends' },
      { label: 'Popular', value: 'popular' },
  ],

  activeOptions: [
      { label: 'Active', value: 1 },
      { label: 'Disabled', value: 0 },
  ],  

  getRoleColor(role){
      if(role == 'org.admin')
        return 'red-8'

      if(role == 'employee')
        return 'cyan-8'

      if(role == 'praktikant')
        return 'green-8'
  },

  date(val, format="DD.MM.YYYY"){
      return val ? moment(val).format(format) : '-//-'
  },

  cleanValue(val, fb='-//-'){
      return val ? val : fb
  },

  clean(val, fb='-//-'){
      return this.cleanValue(val, fb)
  },

}

export default async ( { app } ) => {
  app.config.globalProperties.$helper = helper
}

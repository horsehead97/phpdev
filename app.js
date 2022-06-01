const app= Vue.createApp({
    data(){
        return{
            title: 'Blogs with Dogs',
            author: 'Sparky Jones'
        }
    },
    methods:{
        changeTitle(title){
            console.log('I was clicked')
            this.title=title
        }
    },
    computed:{
        filteredBooks(){
            return "hello"
        }
    }
})
app.mount('#app')
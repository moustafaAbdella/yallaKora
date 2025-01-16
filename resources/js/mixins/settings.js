export const settings = {
    data() {
        return {
            settings: {},
            settings_orginal: {},
        }
    },
    async mounted() {
            let response = await axios.get(url+"/admin/settings/data")
            for (let index = 0; index < response.data.length; index++) {
                const key = response.data[index].name;
                var value = response.data[index].val;
                if(response.data[index].type == "bool"){
                    value = value == 1 ? true:false;
                }
                this.settings[key] = value;
            }
            this.settings_orginal = response.data;
    },
}

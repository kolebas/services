import axios from "axios";
class UserInfo{
  constructor(source){
    this._source = source,
    this._data = []
  }
  async _getData(){
    axios
      .get(this._source, {
        auth: {},
      })
      .then(response => this._data = response.data)
  }
  returnData(){
    await this._getData();
    return this._data;
  }
}

export { UserInfo }
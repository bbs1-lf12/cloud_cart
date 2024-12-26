class BEConnection {
  static API_URL = "http://localhost:8080/api/v1/";
  static instance;

  constructor() {
    if (BEConnection.instance) {
      return BEConnection.instance;
    }
    BEConnection.instance = this;
  }

  async getAllArticles() {
    return await this.callBE('articles', 'GET');
  }

  async callBE(url = '', method = {}, body = null) {
    const res = await fetch(
      BEConnection.API_URL + url,
      {
        method: method,
        body: body,
      }
    );

    if (!res.ok) {
      throw new Error('Error calling the BE.');
    }

    return await res.json();
  }
}

export default BEConnection;

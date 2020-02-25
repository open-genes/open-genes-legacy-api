import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class FavoritesService {

  constructor(
    private http: HttpClient
  ) { }

  items = [];

  addToFavorites(id) {
    this.items.push(id);
  }

  getItems() {
    return this.items;
  }

  clearFavorites() {
    this.items = [];
    return this.items;
  }
}

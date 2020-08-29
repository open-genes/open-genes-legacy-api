import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {Genes} from '../../core/models';
import {ApiService} from '../../core/services/api.service';

@Component({
  selector: 'app-news',
  templateUrl: './news.component.html'
})
export class NewsComponent implements OnInit {
  genes: Genes[];
  error: number;
  portion: number;

  constructor(
    private readonly apiService: ApiService,
    public translate: TranslateService) {
  }

  ngOnInit() {
    this.portion = 10;
    this.getGenes();
  }

  private getGenes() {
    this.apiService.getGenes().subscribe((genes) => {
      this.genes = genes;
    }, error => this.error = error);
  }

  public loadMore(portion: number) {
    if (portion) {
      return this.portion += portion;
    }
  }
}
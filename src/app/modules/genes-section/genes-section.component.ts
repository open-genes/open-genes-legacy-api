import {Component, Input, OnInit} from '@angular/core';
import {Filter, Genes} from 'src/app/core/models';
import {ApiService} from 'src/app/core/services/api.service';
import {TranslateService} from '@ngx-translate/core';

@Component({
  selector: 'app-genes-section',
  templateUrl: './genes-section.component.html',
  styleUrls: ['./genes-section.component.scss']
})

export class GenesSectionComponent implements OnInit {
  @Input() dataSource: Genes[];
  genes: Genes[];
  filters: Filter;
  error: number;

  private expressionTranslates = { // TODO: убрать хардкод
    уменьшается: 'decreased',
    увеличивается: 'increased',
    неоднозначно: 'mixed'
  };

  constructor(
    private readonly apiService: ApiService,
    private readonly translate: TranslateService
  ) {
    this.filters = {
      byName: false,
      byAge: false,
      byClasses: [],
      byExpressionChange: null
    };
  }

  ngOnInit() {
    this.getGenes();
  }

  private getGenes() {
    this.apiService.getGenes().subscribe((genes) => {
      this.genes = genes;
    }, error => this.error = error);
  }

  public filterByFuncClusters(fc: number[]) {
    if (fc.length > 0) {
      this.apiService.getGenesByFunctionalClusters(fc).subscribe((genes) => {
        this.genes = genes;
      });
    } else {
      this.getGenes();
    }
  }

  public filterByExpressionChange(expression: string) {
    if (expression) {
      if (this.translate.currentLang === 'ru') {
        expression = this.expressionTranslates[expression];
      }
      this.apiService.getGenesByExpressionChange(expression).subscribe(genes => {
        this.genes = genes;
      });
    } else {
      this.getGenes();
    }
  }

  /**
   * Сброс фильтров таблицы генов
   */
  public filtersCleared() {
    this.getGenes();
  }
}

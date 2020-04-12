import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {FavoritesComponent} from './favorites.component';
import {RouterModule, Routes} from '@angular/router';
import {TranslateModule} from '@ngx-translate/core';
import {GenesSectionModule} from 'src/app/modules/genes-section/genes-section.module';

const routes: Routes = [
  {path: '', component: FavoritesComponent}
];

@NgModule({
  declarations: [
    FavoritesComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    TranslateModule,
    GenesSectionModule
  ]
})
export class FavoritesModule { }

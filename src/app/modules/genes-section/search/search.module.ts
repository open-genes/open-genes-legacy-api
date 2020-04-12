import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {PipesModule} from 'src/app/modules/pipes/pipes.module';
import { ReactiveFormsModule } from '@angular/forms';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    PipesModule,
    ReactiveFormsModule
  ]
})
export class SearchModule { }

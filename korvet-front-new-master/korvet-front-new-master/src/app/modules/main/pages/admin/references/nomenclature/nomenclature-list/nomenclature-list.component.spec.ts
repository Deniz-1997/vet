import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NomenclatureListComponent } from './nomenclature-list.component';

describe('NomenclatureListComponent', () => {
  let component: NomenclatureListComponent;
  let fixture: ComponentFixture<NomenclatureListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NomenclatureListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NomenclatureListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

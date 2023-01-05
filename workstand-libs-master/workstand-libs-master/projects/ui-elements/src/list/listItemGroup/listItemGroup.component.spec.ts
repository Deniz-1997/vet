import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListItemGroupComponent } from './listItemGroup.component';

describe('ListItemGroupComponent', () => {
  let component: ListItemGroupComponent;
  let fixture: ComponentFixture<ListItemGroupComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListItemGroupComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ListItemGroupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

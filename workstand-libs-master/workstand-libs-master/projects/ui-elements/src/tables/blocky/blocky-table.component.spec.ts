import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BlockyTableComponent } from './blocky-table.component';

describe('BlockyTableComponent', () => {
  let component: BlockyTableComponent;
  let fixture: ComponentFixture<BlockyTableComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BlockyTableComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BlockyTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

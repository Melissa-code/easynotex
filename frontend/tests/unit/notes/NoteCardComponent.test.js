import { describe, it, expect } from 'vitest';
import { render, screen } from '../../../node_modules/@testing-library/vue';
import NoteCardComponent from '../../../src/components/notes/NoteCardComponent.vue';  

describe('NoteCardComponent', () => {
  
  it('displays note title and description', async () => {
    const note = { 
      title: 'Test titre de la note', 
      content: 'Test contenu de la note',
      created_at: '2023-01-01T00:00:00Z',
      updated_at: '2023-02-01T00:00:00Z',
      category_name: 'Test nom de la categorie',
      isFavorite: 1
    };

    render(NoteCardComponent, {
      props: { note },
    });

    expect(await screen.findByText('TEST TITRE DE LA NOTE')).toBeTruthy();
    expect(await screen.findByText('Test contenu de la note')).toBeTruthy();
    expect(await screen.findByText('Modifiée le 01/02/2023')).toBeTruthy();
    expect(await screen.findByText('Test nom de la categorie')).toBeTruthy();
  });
});
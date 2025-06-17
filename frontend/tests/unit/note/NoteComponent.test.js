import { describe, it, expect } from 'vitest';
import { render, screen } from '../../../node_modules/@testing-library/vue';
import NoteComponent from '../../../src/components/note/NoteComponent.vue';

describe('NoteComponent', () => {
  it('devrait afficher le titre et contenu de la note', async () => {
    const note = { 
      id: 1, 
      title: 'Note 1', 
      image: 'image1.png',
      content: 'Contenu 1',
      updated_at: "2025-06-19",
      created_at: "2025-06-19",
      category_name: "Créativité-Loisirs",
      isFavorite: 1,
    }

    await render(NoteComponent, { props: { note } });

    expect(await screen.findByText('NOTE 1')).toBeTruthy();
    expect(await screen.findByText('Contenu 1')).toBeTruthy();
    expect(await screen.findByText(/Catégorie : Créativité-Loisirs/)).toBeTruthy();
    expect(await screen.findByText(/Créée le 19\/06\/2025/)).toBeTruthy();
    const img = await screen.findByRole('img');
    expect(img.getAttribute('src')).to.equal('/uploads/image1.png');
    expect(img.getAttribute('alt')).to.equal('Image de la note n°1');
    expect(await screen.findByText('Retour')).toBeTruthy();
  }); 

  it("n'affiche pas d’image si `note.image` est null", async () => {
    const noteSansImage = {
      id: 2,
      title: 'Note 2',
      image: null,
      content: 'Contenu 2',
      updated_at: '2025-06-19',
      created_at: '2025-06-19',
      category_name: "Créativité-Loisirs",
      isFavorite: 0,
    };

    render(NoteComponent, { props: { note: noteSansImage } });
    expect(screen.queryByRole('img')).toBeNull(); 
  });

  it('affiche "Modifiée le ..." si updated_at > created_at', async () => {
    const note = {
      id: 1,
      title: 'Note 1',
      content: 'Contenu',
      image: null,
      created_at: '2024-01-01',
      updated_at: '2025-01-01',
      category_name: '',
    };

    render(NoteComponent, { props: { note } });
    expect(await screen.findByText(/Modifiée le/)).toBeTruthy();
  });

  it('affiche "Créée le ..." si updated_at <= created_at', async () => {
    const note = {
      id: 2,
      title: 'Note 2',
      content: 'Contenu',
      image: null,
      created_at: '2025-01-01',
      updated_at: '2025-01-01',
      category_name: '',
    };

    render(NoteComponent, { props: { note } });
    expect(await screen.findByText(/Créée le/)).toBeTruthy();
  });

  it('affiche l’icône de favori si isFavorite = 1', async () => {
    const note = {
      id: 5,
      title: 'Note favorite',
      content: 'note content',
      image: null,
      created_at: '2025-01-01',
      updated_at: '2025-01-01',
      category_name: '',
      isFavorite: 1,
    };

    render(NoteComponent, { props: { note } });
    const favoriteIcon = document.querySelector('.favorite-icon svg');
    expect(favoriteIcon).to.exist;
  });

  it('affiche les boutons d’action', async () => {
    const note = {
      id: 6,
      title: 'Note',
      content: '',
      image: null,
      created_at: '2025-01-01',
      updated_at: '2025-01-01',
      category_name: '',
    };

    render(NoteComponent, { props: { note } });
    expect(await screen.findByText('Retour')).toBeTruthy();
    const buttons = screen.getAllByRole('button');
    expect(buttons.length).toBeGreaterThanOrEqual(4);
  });
});

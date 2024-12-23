<?php

include_once 'generique/vue_generique.php';
Class VueGroupe extends VueGenerique{
    public function __construct() {
        parent::__construct();
    }
    public function afficherGroupeSAE($groupeSAE) {
        ?>
        <div class="container mt-4">
            <h2>Gestion des Groupes pour la SAE</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom du Groupe</th>
                    <th>Membres</th>
                    <th>Modifier</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($groupeSAE)) {
                    $currentGroup = null;
                    $members = [];

                    foreach ($groupeSAE as $row) {
                        if ($currentGroup === null || $currentGroup['id_groupe'] !== $row['id_groupe']) {
                            if ($currentGroup !== null) {
                                echo "<tr>";
                                echo "<td>{$currentGroup['nom_groupe']}</td>";
                                echo "<td>" . implode(', ', $members) . "</td>";
                                echo "<td>
                                    <a href='index.php?module=groupeprof&action=versModifierGroupe&idGroupe={$currentGroup['id_groupe']}' class='btn btn-sm btn-secondary'>
                                        <i class='fas fa-cog'></i>
                                    </a>
                                  </td>";
                                echo "</tr>";
                            }
                            $currentGroup = [
                                'id_groupe' => $row['id_groupe'],
                                'nom_groupe' => $row['nom_groupe']
                            ];
                            $members = [];
                        }
                        $members[] = $row['prenom_membre'] . " " . $row['nom_membre'];
                    }

                    if ($currentGroup !== null) {
                        echo "<tr>";
                        echo "<td>{$currentGroup['nom_groupe']}</td>";
                        echo "<td>" . implode(', ', $members) . "</td>";
                        echo "<td>
                            <a href='index.php?module=groupeprof&action=versModifierGroupe&idGroupe={$currentGroup['id_groupe']}' class='btn btn-sm btn-secondary'>
                                <i class='fas fa-cog'></i>
                            </a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun groupe trouvé pour cette SAE</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=groupeprof&action=ajouterGroupeFormulaire" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Groupe
                </a>
            </div>
        </div>
        <?php
    }
    public function formulaireModifierGroupe($detailsGroupe, $tabNvEtudiant, $idGroupe) {
        ?>
        <div class="container mt-4">
            <h2>Modifier le Groupe</h2>
            <form action="index.php?module=groupeprof&action=enregistrerModificationsGroupe" method="post">
                <input type="hidden" name="id_groupe" value="<?php echo htmlspecialchars($detailsGroupe['id_groupe']); ?>">

                <div class="form-group">
                    <label for="nomGroupe">Nom du Groupe</label>
                    <input type="text" id="nomGroupe" name="nomGroupe" class="form-control"
                           value="<?php echo htmlspecialchars($detailsGroupe['nom_groupe']); ?>" required>
                </div>

                <label>Modifiable par le groupe</label>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input
                                type="radio"
                                id="modifiable_oui"
                                name="modifiable_par_groupe"
                                class="form-check-input"
                                value="1"
                            <?php echo $detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="modifiable_oui">Oui</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                                type="radio"
                                id="modifiable_non"
                                name="modifiable_par_groupe"
                                class="form-check-input"
                                value="0"
                            <?php echo !$detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="modifiable_non">Non</label>
                    </div>
                </div>


                <h3>Supprimer des membres</h3>
                <ul>
                    <?php foreach ($detailsGroupe['membres'] as $membre): ?>
                        <li>
                            <input type="checkbox" name="membres_a_supprimer[]" value="<?php echo htmlspecialchars($membre['id_utilisateur']); ?>">
                            <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>
                            (<?php echo htmlspecialchars($membre['email']); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h3>Ajouter des étudiants</h3>
                <div class="form-group mt-3">
                    <label for="etudiants">Sélectionner des Étudiants</label>
                    <select multiple class="form-control" id="etudiants" name="etudiants[]">
                        <?php foreach ($tabNvEtudiant as $etudiant): ?>
                            <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Enregistrer les modifications</button>
                <a href="index.php?module=groupeprof&action=supprimerGrp&idGroupe=<?php echo $idGroupe ?>" class="btn btn-danger mt-4">Supprimer le groupe</a>
                <a href="index.php?module=groupeprof&action=gestionGroupeSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
    public function afficherFormulaireAjoutGroupe($etudiants) {
        ?>
        <div class="container mt-5">
            <h2>Ajouter un Nouveau Groupe</h2>
            <form method="post" action="index.php?module=groupeprof&action=creerGroupe">
                <div class="form-group mt-4">
                    <label for="nom_groupe">Nom du Groupe</label>
                    <input type="text" class="form-control" id="nom_groupe" name="nom_groupe"
                           placeholder="Entrez le nom du groupe" required>
                </div>
                <div class="form-group mt-3">
                    <label for="etudiants">Sélectionner des Étudiants</label>
                    <select multiple class="form-control" id="etudiants" name="etudiants[]">
                        <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-4">Créer le Groupe</button>
                <a href="index.php?module=groupeprof&action=gestionGroupeSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
}
// Safe profile field enhancer that does NOT use Flarum compat modules

(() => {
  if (typeof app === "undefined" || typeof m === "undefined") {
    return; // forum not ready
  }

  app.initializers.add("kmginley-profile-extras", () => {

    // Inject Title + Short Description under username on profile page
    const originalUserCard = app.forum.data.components?.UserCard;

    // Wait until the DOM updates
    const observer = new MutationObserver(() => {
      const card = document.querySelector(".UserCard-info");
      if (!card) return;

      const userId = app.session?.user?.id();
      const viewingUserId = card.closest("[data-user-id]")?.dataset.userId;

      // Fetch attributes
      const user = app.store.getById("users", viewingUserId);
      if (!user) return;

      const title = user.attribute("title");
      const desc = user.attribute("short_description");

      if (title && !document.querySelector(".user-extra-title")) {
        const div = document.createElement("div");
        div.className = "user-extra-title";
        div.textContent = title;
        card.appendChild(div);
      }

      if (desc && !document.querySelector(".user-extra-desc")) {
        const div = document.createElement("div");
        div.className = "user-extra-desc";
        div.textContent = desc;
        card.appendChild(div);
      }

      // Add simple edit button for own profile
      if (userId && userId === viewingUserId && !document.querySelector(".edit-profile-extras-btn")) {
        const btn = document.createElement("button");
        btn.textContent = "Edit Profile Info";
        btn.className = "Button Button--link edit-profile-extras-btn";
        btn.onclick = () => {
          const newTitle = prompt("Title", title || "");
          if (newTitle === null) return;
          const newDesc = prompt("Short description", desc || "");
          if (newDesc === null) return;

          user.save({
            title: newTitle.trim() || null,
            short_description: newDesc.trim() || null
          }).then(() => {
            app.alerts.show({ type: "success" }, "Saved");
            location.reload();
          });
        };

        card.appendChild(btn);
      }

    });

    observer.observe(document.body, { childList: true, subtree: true });

  });
})();
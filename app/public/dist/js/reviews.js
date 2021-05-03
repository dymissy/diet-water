(function () {
    String.prototype.capitalize = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    const Reviews = function (rootNode) {
        this.apiEndpoint = '/api/v1/reviews?page=';
        this.rootNode = rootNode;
        this.reviewContainer = rootNode.getElementsByClassName('reviews-inner')[0];
        this.loadMoreButton = rootNode.getElementsByClassName('reviews-more')[0];
        this.currentPage = 0;
        this.limit = 2;

        this.loadMoreButton.addEventListener("click", e => {
            this.load()
        });
    }

    Reviews.prototype.load = function () {
        const self = this;
        let preventLoading = false;

        fetch(this.apiEndpoint + this.currentPage).then(function (response) {
            return response.json();
        })
            .then(function (body) {
                self.rootNode.style.display = "block";
                self.currentPage++;

                return body;
            })
            .then(function (reviews) {
                if (reviews.length < self.limit) {
                    preventLoading = true;
                }

                self.render(reviews);
            })
            .catch((error) => {
                console.log(error);
            })
            .finally(() => {
                if (preventLoading) {
                    self.loadMoreButton.style.display = 'none';

                    return;
                }

                self.loadMoreButton.style.display = 'inline-flex';
            })
    }

    Reviews.prototype.render = function (reviews) {
        let reviewsNode = document.createElement('div')
        reviewsNode.className = 'review-extended feature-extended';
        reviewsNode.innerHTML = reviews.reduce((content, review) => {
            const date = new Date(review.created_at);

            const reviewContent = `<div class="review-${review.id} review-body">
                <h5>${review.title}</h5>
                <p><em>${review.author} on ${date.toLocaleDateString()} said:</em><br> ${review.content}</p>
                
                <small>source: ${review.source.capitalize()} - rate: ${review.rate}/5</small>
            </div>`;

            return content + reviewContent;
        }, '');

        this.reviewContainer.appendChild(reviewsNode)
    }

    window.addEventListener('load', function () {
        const reviews = new Reviews(document.querySelector('.reviews'));

        reviews.load();
    })
}())
